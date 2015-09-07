<div id="page-wrapper" class="gray-bg">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2><?php echo ($action == 'add')?'Ecrire':'Editer'; ?> un article</h2>
		</div>
		<div class="col-lg-2">

		</div>
	</div>
<div class="wrapper wrapper-content">
	<form method="post" action="articles.php?<?php echo ($action == 'add')?'add=true':'edit='.getVar('edit'); ?>" class="form-horizontal" id="editorForm" enctype="multipart/form-data">
		<div class="row">
			<div class="col-lg-8" >
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Editeur</h5>
					</div>
					<div class="ibox-content no-padding">
						<textarea class="summernote" name="content" rows="18"><?php if ($action == 'edit') echo $article['content']; ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-lg-4" >
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Options</h5>
					</div>
					<div class="ibox-content no-padding">
						<div class="form-group"><label class="col-sm-3 control-label">Titre</label>
							<div class="col-sm-9"><input type="text" name="title" class="form-control" <?php if ($action == 'edit') echo 'value="'.$article['title'].'"'; ?>></div>
						</div>
						<div class="form-group"><label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-9"><input type="text" name="description" class="form-control"  <?php if ($action == 'edit') echo 'value="'.$article['description'].'"'; ?>></div>
						</div>
						<div class="form-group"> <label class="col-sm-3 control-label">Categorie</label>
							<div class="col-sm-9">
								<select data-placeholder="" name="category" class="chosen-select col-sm-12" tabindex="4">
									<?php foreach ($categories as $category) echo '<option value="'.$category['id'].'" '.(($action == 'edit' && $category['id'] == $article['category_id'])?'selected="selected"':'').'>'.$category['name'].'</option>'; ?>
								</select>
							</div>
						</div>
						<div class="form-group"> <label class="col-sm-3 control-label">Tags</label>
							<div class="col-sm-9">
								<select data-placeholder="(Plusieurs tags possible)" name="tags[]" class="chosen-select col-sm-12" multiple tabindex="4">
									<?php foreach ($tags as $tag) echo '<option value="'.$tag['id'].'" '.(($action == 'edit' && in_array($tag['id'], $articleTags))?'selected="selected"':'').'>'.$tag['name'].'</option>'; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-9 col-sm-offset-3">
								<button class="btn btn-success" type="submit">Enregistrer</button>
								<button class="btn btn-white" type="submit">Annuler</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
