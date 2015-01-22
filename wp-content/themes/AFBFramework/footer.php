<div class="clearfix"></div>
<footer>
    <div class="footer-text">
        <div class="container">
            <div class="col-xs-12">
                    <?php dynamic_sidebar('footer'); ?>
            </div>
        </div>
    </div>
</footer>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.mmenu.min.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        //Setup mobile nav
        jQuery("#mmobile-nav").mmenu({
            offCanvas: {
                position: "left",
                zposition: "front"
            }
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#mmobile-nav").mmenu();
        jQuery(".close-mmenu").click(function () {
            jQuery("#mmobile-nav").trigger("close.mm");
        });
    });
</script>
<?php if (is_page('89')) : ?>
    <script>
            jQuery('nav').addClass('').css('display', 'none');
    </script>
<? endif; ?>
</div> <!-- the wrapper end -->
<?php wp_footer(); ?>

</body>
</html>

