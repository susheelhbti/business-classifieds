<template>
	<div>
		<a href="javascript:void(0)" @click.prevent="postBookmark">
			<span class="fa" :class="[bookmarked ? 'fa-bookmark' : 'fa-bookmark-o']"></span>
		</a>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
	name: "bookmark-button",
	props: ["post"],
	data() {
		return {
			bookmarked: false,
			postponeBookmark: false
		};
	},
	computed: {
		...mapGetters(["isLoggedIn", "loginSuccess", "currentUser"])
	},
	created() {
		if (this.isLoggedIn) {
			this.checkBookmark();
		}
	},
	watch: {
		isLoggedIn: function(newValue, oldValue) {
			if (newValue && !this.currentUser.is_admin) {
				this.checkBookmark();
			}
			/*
            if(newValue && this.postponeBookmark === true && this.bookmarked == false) {
				this.postponeBookmark = false;
				this.postBookmark();
			}
			*/
			if (!newValue) {
				this.bookmarked = false;
			}
		},
		loginSuccess: function(newValue, oldValue) {}
	},
	methods: {
		postBookmark() {
			if (!this.isLoggedIn) {
				this.postponeBookmark = true;
				this.$store.dispatch("showLoginModal");
			} else {
				if (!this.currentUser.is_admin) {
					axios
						.post("/spa/user/bookmarks/" + this.post.id)
						.then(res => {
							if (res.data.bookmark == true) {
								this.bookmarked = true;

								this.$store.commit("alert", {
									message: "Saved to bookmarks.",
									type: "success"
								});
							} else {
								this.bookmarked = false;

								this.$store.commit("alert", {
									message: "Removed from bookmarks.",
									type: "success"
								});
							}
						});
				}
			}
		},
		checkBookmark() {
			if (!this.currentUser.is_admin) {
				axios
					.get("/spa/user/bookmarks/check/" + this.post.id)
					.then(res => {
						if (res.data.bookmarked == true) {
							this.bookmarked = true;
						} else {
							this.bookmarked = false;
						}
					});
			}
		}
	}
};
</script>

