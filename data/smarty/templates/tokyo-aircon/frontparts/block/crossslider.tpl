<script type="text/javascript" src="/js/jquery.cross-slide.js"></script>

<script type="text/javascript">
$(document).ready( function(){
$("div#slidegallery").crossSlide({
loop:1,
fade: 2
}, [
{ 
src: 'images/top_slider/tp_main_g.jpg',
from: '50% 50% 1x',
to:   '50% 50% 1x',
time: 7
},{ 
src: 'images/top_slider/tp_main2.jpg',
from: '50% 50% 1x',
to:   '50% 50% 1x',
time: 5
}
,{ 
src: 'images/top_slider/tp_main3.jpg',
from: '50% 50% 1x',
to:   '50% 50% 1x',
time: 10
}
])
});
</script>

<!-- CSS -->
<style type="text/css">
  div#slidegallery { margin: 10px 0 10px 0; width:980px; height:260px; }
</style>