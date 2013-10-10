<div class="modal-dialog modal-lightbox calendar-modal">
	<div class="modal-content">
		<div class="modal-header"><button type="button" class="close btn-lg" data-dismiss="modal" aria-hidden="true">&times;</button></div>
		<div class="modal-body">
			<div class="row">
				<div class="col-lg-4">
					<div class="master-photo"></div><img src="/images/src/master.jpg" class="img-circle" width="240" height="240">
					<div class="text-center">
						<span>Запись по телефону:</span>
						<h2 class="phone green">(383) 230-01-80</h2>
					</div>
				</div>
				<div class="col-lg-8">
					<h1>Олеся Морозова</h1>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempora, soluta, ex, dolor, nulla quibusdam assumenda fugit nostrum voluptatibus vel molestias cum culpa incidunt repellat tenetur consequuntur quaerat dolorem. Quisquam, accusantium? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt, provident, cupiditate iusto est saepe laudantium dolor. Ullam, dolores beatae perferendis quam illo voluptas dicta pariatur adipisci dolor hic sapiente nostrum.</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi, error, excepturi aut esse porro explicabo amet quod impedit nemo cumque recusandae cum dicta ipsa libero eum blanditiis omnis rem voluptatum!</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam, dolore, soluta, est officia quaerat nemo dolorem beatae asperiores dicta aliquam rem sit enim nostrum porro natus qui consequatur exercitationem cumque. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt, maxime, omnis eveniet totam neque esse soluta eos ex inventore harum accusantium dolorum asperiores. Consectetur nemo iste reprehenderit quae dicta porro!</p>
    					<div id="disqus_thread"></div>
    					<script>
					dsq.onload = function(){
						DISQUS.reset({
							reload: true,
							config: function () {  
								this.page.identifier = "<?=md5(rand(1, 100000));?>";  
								this.page.url = "http://calendar.local/#!<?=md5(rand(1, 100000));?>";
							}
						});
					};
					</script>
				</div>
			</div>
		</div>
	</div>
</div>