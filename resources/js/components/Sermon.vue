<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1">
	 
		<div class="d-flex flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black video-content">
<!-- 			<div class="px-40 py-20">
				<span class="back text-white" v-on:click="goBack">back</span>
			</div> -->
			<vimeo-player 
				v-if="showVideo"
				v-bind:video-id="sermon.vimeo_id"
				ref="video"
			/> 
		</div>

		<div
			v-html="sermon.description"
			class="py-40 px-40 overflow-y bg-light-grey video-sidebar"
		></div>
	</div>
</template>

<script>
	import { mapMutations } from 'vuex'
	import VimeoPlayer from '../components/VimeoPlayer'

	export default {
		components: {
			VimeoPlayer
		},
		data: function() {
			return {
				sermon: {},
				showVideo: false,
				previousPage: {}
			}
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get(process.env.MIX_APP_URL + '/w/api/sermons/' + to.params.sermon_id)
				.then(response => {
					next(vm => {
						vm.setData(from, response.data)
					});
				})
				.catch(error => {
					alert('Somethin went wrong. Reload the page.');
				});
		},
		methods: {
			...mapMutations([
				'shouldShowHeader'
			]),
			setData: function(from, data) {
				this.previousPage = from;
				this.sermon = data;
				this.showVideo = true;

				if (this.previousPage.name !== null) {
					console.log('hide header');
					this.shouldShowHeader(false);
				}
			},
			// loadSermon: function(sermonId) {
			// 	axios
			// 		.get(process.env.MIX_APP_URL + '/w/api/sermons/' + sermonId)
			// 		.then(response => {
			// 			this.sermon = response.data.sermon;
			// 			this.showVideo = true;
			// 			console.log(response.data.sermon);
			// 		});
			// },
	        // videoEnded: function() {
	        //     this.showVideo = false;
	        //     console.log('video ended');
	        // },
	        goBack: function() {
	        	this.$router.go(-1);
	        }
		},
		created: function() {
			
			//this.loadSermon(this.$route.params.sermon_id);

			// let sermon = this.$store.getters.getSermonWithId(this.sermonId);

			// if (typeof sermon === 'undefined') {
			// 	this.loadSermon();
			// 	console.log('yes undefined');
			// } else {
			// 	this.sermon = sermon;
			// 	this.showVideo = true;
			// 	console.log('show video');
			// }
		},
		destroyed: function() {
			this.shouldShowHeader('true');
		}
	}
</script>