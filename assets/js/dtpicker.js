jQuery(document).ready(function(){
    jQuery.datetimepicker.setDateFormatter('moment');
	jQuery.datetimepicker.setLocale(datepickeropts.locale);

	if(datepickeropts.preventkeyboard == 'on'){
		jQuery(datepickeropts.selector).focus(function() {
			jQuery( this ).blur();
		});
	}

	var opts = {
        value: datepickeropts.value,
        format:datepickeropts.format,
        formatDate: datepickeropts.dateformat,
		formatTime: datepickeropts.hourformat,
        theme: datepickeropts.theme,
        timepicker: (datepickeropts.timepicker == 'on'),
        datepicker: (datepickeropts.datepicker == 'on'),
        step: parseInt(datepickeropts.step),
		timepickerScrollbar: true,
		dayOfWeekStart: parseInt(datepickeropts.dayOfWeekStart),
	};

	if( datepickeropts.minTime !== '' ){
		opts.minTime = datepickeropts.minTime;
	}

	if( datepickeropts.maxTime !== '' ){
		opts.maxTime = datepickeropts.maxTime;
	}

	if( datepickeropts.minDate === 'on' ){
		opts.minDate = 0;
	}

    jQuery(datepickeropts.selector).datetimepicker( opts );
});


