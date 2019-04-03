// trigger datetimepicker on document ready and ajax complete ( when fields are loaded in lightboxes )
jQuery(document).ready(function(){
	dtp_init();
}).ajaxComplete( function(){
	dtp_init();
});

function dtp_init() {

	//removed in 1.7.3 in favour of php format
	//jQuery.datetimepicker.setDateFormatter('moment');

	jQuery.datetimepicker.setLocale(datepickeropts.locale);

	if(datepickeropts.preventkeyboard == 'on'){
		jQuery(datepickeropts.selector).focus(function() {
			jQuery( this ).blur();
		});
	}

	// custom times logic
	var logic = function( currentDateTime) {};

	if( datepickeropts.timepicker === 'on' && datepickeropts.allowed_times !== '' ){
		logic = function( currentDateTime, $input ){

			if( currentDateTime.getDay()==0 && datepickeropts.sunday_times !== '' ){
				this.setOptions({
					allowTimes:datepickeropts.sunday_times
				});
			} else if( currentDateTime.getDay()==1 && datepickeropts.monday_times !== '' ){
				this.setOptions({
					allowTimes:datepickeropts.monday_times
				});
			} else if( currentDateTime.getDay()==2 && datepickeropts.tuesday_times !== '' ){
				this.setOptions({
					allowTimes:datepickeropts.tuesday_times
				});
			} else if( currentDateTime.getDay()==3 && datepickeropts.wednesday_times !== '' ){
				this.setOptions({
					allowTimes:datepickeropts.wednesday_times
				});
			} else if( currentDateTime.getDay()==4 && datepickeropts.thursday_times !== '' ){
				this.setOptions({
					allowTimes:datepickeropts.thursday_times
				});
			} else if( currentDateTime.getDay()==5 && datepickeropts.friday_times !== '' ){
				this.setOptions({
					allowTimes:datepickeropts.friday_times
				});
			} else if( currentDateTime.getDay()==6 && datepickeropts.saturday_times !== '' ){
				this.setOptions({
					allowTimes:datepickeropts.saturday_times
				});
			} else {
				if( datepickeropts.allowed_times !== ''){
					this.setOptions({
						allowTimes:datepickeropts.allowed_times
					});
				} else {

					this.setOptions({
						allowTimes:[],
						step: parseInt(datepickeropts.step),
					});

					if( datepickeropts.minTime !== '' ){
						this.setOptions({
							minTime:datepickeropts.minTime
						});
					}

					if( datepickeropts.maxTime !== '' ){
						this.setOptions({
							maxTime:datepickeropts.maxTime
						});
					}
				}
			}
		};
	}

	// fix formatter issue
	datepickeropts.format = dtp_cleanup_format( datepickeropts.format );
	datepickeropts.dateformat = dtp_cleanup_format( datepickeropts.dateformat );
	datepickeropts.hourformat = dtp_cleanup_format( datepickeropts.hourformat );

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
		onChangeDateTime:logic,
		onShow:logic
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

	if( datepickeropts.disabled_days !== ''){
		opts.disabledWeekDays = datepickeropts.disabled_days;
	}

	if( datepickeropts.allowed_times !== ''){
		opts.allowTimes = datepickeropts.allowed_times;
	}

	jQuery(datepickeropts.selector).datetimepicker( opts );

}

// to replace moment formatter which was causing issues in some languages
function dtp_cleanup_format( format ){

	format = format.replace(/DD/g, 'd').replace(/MM/g, 'm').replace(/YYYY/g, 'Y').replace(/HH/g, 'H').replace(/hh/g, 'h').replace(/mm/g, 'i');
	return format;

}
