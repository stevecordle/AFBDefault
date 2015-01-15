<?php if(is_404()): ?>
<aside id="404-sidebar" class="col-xs-12 col-md-4 hidden-tablet">
    <?php dynamic_sidebar('sidebar'); ?>
</aside>
<?php else: ?>
<aside id="page-sidebar" class="col-xs-12 col-md-4 hidden-tablet">
    <?php dynamic_sidebar('sidebar'); ?>
</aside>
<?php endif; ?>