// trigger datetimepicker on document ready and ajax complete ( when fields are loaded in lightboxes )
jQuery(document).ready(function(){
	dtp_init();
}).ajaxComplete( function(){
	dtp_init();
});

function dtp_init() {

	jQuery.datetimepicker.setDateFormatter('moment');
	jQuery.datetimepicker.setLocale(datepickeropts.locale);

	if(datepickeropts.preventkeyboard == 'on'){
		jQuery(datepickeropts.selector).focus(function() {
			jQuery( this ).blur();
		});
	}

	// convert to integer
	datepickeropts.offset = parseInt( datepickeropts.offset );

	// custom times logic
	var logic = function( currentDateTime, $input ) {

		$input.datetimepicker( { value: $input.val() } );

		if( datepickeropts.minDate === 'on' ) {

			var now = new Date();

			if( currentDateTime.toDateString() === now.toDateString() ){
				var futureh = new Date( now.getTime() + datepickeropts.offset * 60000 );
				var mint =  datepickeropts.minTime.split(':');

				if( parseInt( futureh.getHours() ) > parseInt( mint[0] ) ) {

					this.setOptions({
						minTime: futureh.getHours() + ':' + futureh.getMinutes()
					});

				} else {
					this.setOptions({
						minTime: datepickeropts.minTime
					});
				}

			} else {
				this.setOptions({
					minTime: datepickeropts.minTime
				});
			}

		}

	};

	if( datepickeropts.timepicker === 'on' && datepickeropts.allowed_times !== '' ){
		logic = function( currentDateTime, $input ){

			$input.datetimepicker( { value: $input.val() } );

			if( datepickeropts.minDate === 'on' ) {

				var now = new Date();

				if( currentDateTime.toDateString() === now.toDateString() ){
					var futureh = new Date( now.getTime() + datepickeropts.offset * 60000 );
					this.setOptions({
						minTime: futureh.getHours() + ':' + futureh.getMinutes()
					});
				} else {
					this.setOptions({
						minTime: datepickeropts.minTime
					});
				}

			}

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
		onShow:logic,
		validateOnBlur:false //added on 1.7.4 to prevent AM/PM format from jumping to 1h before.
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
