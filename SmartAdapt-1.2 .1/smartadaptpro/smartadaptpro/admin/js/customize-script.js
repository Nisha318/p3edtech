jQuery(document).ready(function ($) {

	console.log($(".smartadapt_theme_options_smartadapt_layout_width").length);
	$(".smartadapt_theme_options_smartadapt_layout_width").noUiSlider({


		range:[1000, 1280], slide:triggerChange, start:$("#smartadapt_theme_options_smartadapt_layout_width").val(), handles:1, step: 1, serialization:{
			to:$("#smartadapt_theme_options_smartadapt_layout_width"),  resolution: 1
		}

	});

	$(".smartadapt_theme_options_smartadapt_sidebar_resize").noUiSlider({

		range:[250, 350], slide:triggerChange, start:$("#smartadapt_theme_options_smartadapt_sidebar_resize").val(), handles:1, step: 1, serialization:{
			to:$("#smartadapt_theme_options_smartadapt_sidebar_resize"), resolution: 1
		}

	});


});

function triggerChange() {
	jQuery('input').change();






}