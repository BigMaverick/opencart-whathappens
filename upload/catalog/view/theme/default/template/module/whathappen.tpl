<div>
  <h2><?php echo $heading_title; ?></h2>
  
  <ul class="whathappen">
  <?php $icons=array('user','plus','bullhorn','arrow-down','truck','tags','shopping-cart','comment'); ?>
  <?php foreach ($whathappens as $whathappen) { ?>
  			<li>
			<div style="float: left;" >
			<i class="fa fa-<?php echo $icons[$whathappen['status']-1]; ?>"></i>
			<?php echo $whathappen['name'].' '.(!empty($whathappen['last_name']) ? mb_substr($whathappen['last_name'],0,1,'UTF-8').'. ' : ' '); ?>
			<?php if (!empty($whathappen['url'])) { ?>
				<a href="<?php echo $whathappen['url']; ?>" alt="<?php $whathappen['product']; ?>">
				<?php echo mb_substr($whathappen['product'],0, $namelength,'UTF-8').'...'; ?>	
				</a>
			<?php } ?>
			</div>
			<br/>
			<span style="margin-left:-50px; text-align:left"><?php echo $whathappen['status_text']; ?></span>
			<span style="font-size: 10px; text-align:right;float:right"><?php echo $whathappen['time_text']; ?></span>
			</li>
		<?php } ?>
	</ul>
</div>
<style>
.whathappen {text-align: center; margin-left: 0; padding-left: 0;}
.whathappen li {list-style: none; color: #668626;
    border-bottom:1px solid #C2C2C2;
    padding:5px;
    margin:2px;

}
.spyWrapper {
	margin-left: 0;
    overflow: hidden;
    position: relative;    
}
</style>
<script type="text/javascript" charset="utf-8">
$(function () {
    $('ul.whathappen').simpleSpy();
});

(function ($) {
    
$.fn.simpleSpy = function (limit, interval) {
    limit = limit || 4;
    interval = interval || 4000;
    
    return this.each(function () {
        // 1. setup
            // capture a cache of all the list items
            // chomp the list down to limit li elements
        var $list = $(this),
            items = [], // uninitialised
            currentItem = limit,
            total = 0, // initialise later on
            height = 10+$list.find('> li:first').height();
            
        // capture the cache
        $list.find('> li').each(function () {
            items.push('<li>' + $(this).html() + '</li>');
        });
        
        total = items.length;
        
       $list.wrap('<div class="spyWrapper" />').parent().css({ "height":"300px"});
        
        $list.find('> li').filter(':gt(' + (limit - 1) + ')').remove();

        // 2. effect        
        function spy() {
            // insert a new item with opacity and height of zero
            var $insert = $(items[currentItem]).css({
                height : 0,
                opacity : 0,
                display : 'none'
            }).prependTo($list);
                        
            // fade the LAST item out
            $list.find('> li:last').animate({ opacity : 0}, 1000, function () {
                // increase the height of the NEW first item
                $insert.show().animate({ height : height }, 1000).animate({ opacity : 1 }, 1000);
                
                // AND at the same time - decrease the height of the LAST item
                // $(this).animate({ height : 0 }, 1000, function () {
                    // finally fade the first item in (and we can remove the last)
                    $(this).remove();
                // });
            });
            
            currentItem++;
            if (currentItem >= total) {
                currentItem = 0;
            }
            
            setTimeout(spy, interval)
        }
        
        spy();
    });
};
    
})(jQuery);



</script>
