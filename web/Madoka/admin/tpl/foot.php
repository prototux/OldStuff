		<div class="footer">
			<div class="pull-right">
				<strong>Madoka</strong> <?php echo 'V'.VERSION_MARK.'-'.VERSION_SUB; ?>.
			</div>
			<div>
				<strong>Copyright</strong> FabLab Robert-Houdin &copy; <?php echo date('Y'); ?>
			</div>
		</div>

		</div>
		</div>



	<!-- Mainly scripts -->
	<script src="js/jquery-2.1.1.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
	<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

	<!-- Custom and plugin javascript -->
	<script src="js/inspinia.js"></script>

	<!-- SUMMERNOTE -->
	<script src="js/plugins/summernote/summernote.min.js"></script>
	<script src="js/plugins/summernote/summernote-ext-video.js"></script>
	<script src="js/plugins/summernote/french.js"></script>

	<script src="js/plugins/chosen/chosen.jquery.js"></script>

	<script>
		$(document).ready(function()
		{
			$('.summernote').summernote({
				lang: 'fr-FR',
				height: 600,
				tabsize: 4,
				toolbar: [
					["undo",["undo"]],
					["style",["style"]],
					["font",["bold", "italic", "underline", "clear"]],
					["color",["color"]],
					["para",["ul", "ol", "paragraph", "hr"]],
					["table",["table"]],
					["insert",["link", "picture", "video"]],
					["view",["fullscreen", "codeview"]]
				]
			});

			var config = {
				'.chosen-select'           : {disable_search_threshold: 10},
				'.chosen-select-deselect'  : {allow_single_deselect:true},
				'.chosen-select-no-single' : {disable_search_threshold:10},
				'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
				'.chosen-select-width'     : {width:"95%"}
			}
			for (var selector in config)
				$(selector).chosen(config[selector]);
	   });
	</script>

</body>

</html>
