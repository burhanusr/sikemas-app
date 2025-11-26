class DiscussionManager {
    constructor() {
        this.userId = null;
        this.csrfToken = null;
        this.currentAdminId = null;
        this.currentDiscussionId = null;
        this.lastMessageId = 0;

        this.updateInterval = null;
        this.POLLING_RATE = 1000; // 1 seconds

        this.onUnreadCountsUpdated = null;
        this.onNewMessageReceived = null;
        this.onDiscussionsUpdated = null;
    }

    init(userId, csrfToken) {
        this.userId = userId;
        this.csrfToken = csrfToken;
        this.startGlobalPolling();
    }

    startGlobalPolling() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }

        this.updateInterval = setInterval(async () => {
            await this.updateAll();
        }, this.POLLING_RATE);

        this.updateAll();
    }

    async updateAll() {
        try {
            await this.updateUnreadCounts();

            const headerModal = document.getElementById(
                "headerDiscussionModal"
            );
            const laporanModal = document.getElementById("discussionModal");
            const isHeaderOpen =
                headerModal && !headerModal.classList.contains("hidden");
            const isLaporanOpen =
                laporanModal && !laporanModal.classList.contains("hidden");

            if (isHeaderOpen || isLaporanOpen) {
                await this.updateDiscussionsList();
            }

            if (this.currentDiscussionId) {
                await this.updateMessages();
            }
        } catch (error) {
            console.error("Error updating:", error);
        }
    }

    async updateUnreadCounts() {
        const discussions = await this.fetchDiscussions();

        const unreadByAdmin = {};
        discussions.forEach((d) => {
            if (!unreadByAdmin[d.admin_id]) {
                unreadByAdmin[d.admin_id] = 0;
            }
            unreadByAdmin[d.admin_id] += d.unread_count || 0;
        });

        const totalUnread = Object.values(unreadByAdmin).reduce(
            (sum, count) => sum + count,
            0
        );

        if (this.onUnreadCountsUpdated) {
            this.onUnreadCountsUpdated(unreadByAdmin, totalUnread);
        }
    }

    async updateDiscussionsList() {
        const discussions = await this.fetchDiscussions(this.currentAdminId);

        if (this.onDiscussionsUpdated) {
            this.onDiscussionsUpdated(discussions);
        }

        return discussions;
    }

    async updateMessages() {
        if (!this.currentDiscussionId || !this.lastMessageId) return;

        const newMessages = await this.fetchNewMessages(
            this.currentDiscussionId,
            this.lastMessageId
        );

        if (newMessages.length > 0) {
            this.lastMessageId = newMessages[newMessages.length - 1].id;

            if (this.onNewMessageReceived) {
                this.onNewMessageReceived(newMessages);
            }
        }
    }

    async fetchDiscussions(adminId = null) {
        try {
            const url = adminId
                ? `/discussions?admin_id=${adminId}`
                : "/discussions";

            const response = await fetch(url, {
                headers: {
                    "X-CSRF-TOKEN": this.csrfToken,
                    Accept: "application/json",
                },
            });
            return await response.json();
        } catch (error) {
            console.error("Error fetching discussions:", error);
            return [];
        }
    }

    async fetchDiscussionById(discussionId) {
        try {
            const response = await fetch(`/discussions/${discussionId}`, {
                headers: {
                    "X-CSRF-TOKEN": this.csrfToken,
                    Accept: "application/json",
                },
            });
            return await response.json();
        } catch (error) {
            console.error("Error fetching discussion:", error);
            return null;
        }
    }

    async fetchNewMessages(discussionId, lastMessageId) {
        try {
            const response = await fetch(
                `/discussions/${discussionId}/new-messages?last_message_id=${lastMessageId}`,
                {
                    headers: {
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                    },
                }
            );
            return await response.json();
        } catch (error) {
            console.error("Error fetching new messages:", error);
            return [];
        }
    }

    async createDiscussion(adminId, subject, message) {
        try {
            const response = await fetch("/discussions", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    admin_id: adminId,
                    subject: subject,
                    message: message,
                }),
            });

            if (response.ok) {
                const discussion = await response.json();
                // Trigger immediate update
                await this.updateAll();
                return discussion;
            }
            return null;
        } catch (error) {
            console.error("Error creating discussion:", error);
            return null;
        }
    }

    async sendMessage(discussionId, message) {
        try {
            const response = await fetch(
                `/discussions/${discussionId}/messages`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                    },
                    body: JSON.stringify({ message }),
                }
            );

            if (response.ok) {
                const result = await response.json();
                // Trigger immediate update
                await this.updateAll();
                return result;
            }
            return null;
        } catch (error) {
            console.error("Error sending message:", error);
            return null;
        }
    }

    setCurrentAdmin(adminId) {
        this.currentAdminId = adminId;

        if (this.currentDiscussionId) {
            this.currentDiscussionId = null;
            this.lastMessageId = 0;
        }
    }

    setCurrentDiscussion(discussionId) {
        this.currentDiscussionId = discussionId;
        this.lastMessageId = 0;
    }

    getCurrentAdmin() {
        return this.currentAdminId;
    }

    getCurrentDiscussion() {
        return this.currentDiscussionId;
    }

    clearCurrentAdmin() {
        this.currentAdminId = null;
        this.currentDiscussionId = null;
        this.lastMessageId = 0;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));

        if (days === 0) return "Hari ini";
        if (days === 1) return "Kemarin";
        if (days < 7) return `${days} hari lalu`;

        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    }

    formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML.replace(/\n/g, "<br>");
    }

    cleanup() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }

        this.currentAdminId = null;
        this.currentDiscussionId = null;
        this.lastMessageId = 0;
    }
}

window.discussionManager = new DiscussionManager();
