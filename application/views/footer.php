				</section>
			</div>
			<?php if($isUserAdmin) { ?>
				<div class="span3">
					<?php include APP_DIR.'views/sidebar.php'; ?>
				</div>
			<?php } ?>
		</div>
	</div>

	<script src="/static/js/bootstrap.min.js"></script>
	<script src="/static/js/jquery.prettyPhoto.js"></script>

	<script type="text/javascript" charset="utf-8">
		var width = $(window).width();
		var height = $(window).height();
		$(document).ready(function() {
			$("a[rel^='prettyPhoto']").prettyPhoto({
				social_tools: '',
				allow_resize: true,
				default_width: width,
				default_height: height
			});
		});

	</script>

</body>

</html>