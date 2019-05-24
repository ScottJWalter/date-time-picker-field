// trigger datetimepicker on document ready and ajax complete ( when fields are loaded in lightboxes )
jQuery(document).ready(function(){
	dtp_init();
}).ajaxComplete( function(){
	dtp_init();
});

function dtp_init() {

	jQuery.datetimepicker.setDateFormatter('moment');

	if(datepickeropts.preventkeyboard == 'on'){
		jQuery(datepickeropts.selector).focus(function() {
			jQuery( this ).blur();
		});
	}

	// convert to integer
	datepickeropts.offset = parseInt( datepickeropts.offset );

	// custom times logic
	var logic = function( currentDateTime, $input ) {

		var mtime = '';
		$input.datetimepicker( { value: $input.val() } );

		if( datepickeropts.minDate === 'on' ) {

			var now = moment( datepickeropts.now, datepickeropts.format ).toDate();

			if( currentDateTime.toDateString() === now.toDateString() ){

				var futureh = new Date( now.getTime() + datepickeropts.offset * 60000 );
				var mint =  datepickeropts.minTime.split(':');

				if( parseInt( futureh.getHours() ) > parseInt( mint[0] ) ) {
					mtime = futureh.getHours() + ':' + futureh.getMinutes();
					this.setOptions({
						minTime: mtime
					});

				} else {
					mtime = datepickeropts.minTime;
					this.setOptions({
						minTime: mtime
					});
				}

			} else {
				mtime = datepickeropts.minTime;
				this.setOptions({
					minTime: mtime
				});
			}

		}

	};

	// if there's a predefined set of allowed times
	if( datepickeropts.timepicker === 'on' && datepickeropts.allowed_times !== '' ){

		logic = function( currentDateTime, $input ){

			var mtime = '';

			$input.datetimepicker( { value: $input.val() } );

			if( datepickeropts.minDate === 'on' ) {

				var now = moment( datepickeropts.now, datepickeropts.format ).toDate();

				if( currentDateTime.toDateString() === now.toDateString() ){
					var futureh = new Date( now.getTime() + datepickeropts.offset * 60000 );
					mtime = futureh.getHours() + ':' + futureh.getMinutes();
					this.setOptions({
						minTime: mtime
					});
				} else {
					mtime = datepickeropts.minTime;
					this.setOptions({
						minTime: mtime
					});
				}
			}

			var atimes = '';
			if( currentDateTime.getDay()==0 && datepickeropts.sunday_times !== '' ){
				atimes = datepickeropts.sunday_times;
				this.setOptions({
					allowTimes: atimes
				});
			} else if( currentDateTime.getDay()==1 && datepickeropts.monday_times !== '' ){
				atimes = datepickeropts.monday_times;
				this.setOptions({
					allowTimes: atimes
				});
			} else if( currentDateTime.getDay()==2 && datepickeropts.tuesday_times !== '' ){
				atimes = datepickeropts.tuesday_times;
				this.setOptions({
					allowTimes: atimes
				});
			} else if( currentDateTime.getDay()==3 && datepickeropts.wednesday_times !== '' ){
				atimes = datepickeropts.wednesday_times;
				this.setOptions({
					allowTimes: atimes
				});
			} else if( currentDateTime.getDay()==4 && datepickeropts.thursday_times !== '' ){
				atimes = datepickeropts.thursday_times;
				this.setOptions({
					allowTimes: atimes
				});
			} else if( currentDateTime.getDay()==5 && datepickeropts.friday_times !== '' ){
				atimes = datepickeropts.friday_times;
				this.setOptions({
					allowTimes: atimes
				});
			} else if( currentDateTime.getDay()==6 && datepickeropts.saturday_times !== '' ){
				atimes = datepickeropts.saturday_times;
				this.setOptions({
					allowTimes: atimes
				});
			} else {
				atimes = datepickeropts.allowed_times;
				this.setOptions({
					allowTimes: atimes
				});
			}

			var minDateTime = new Date( currentDateTime.getTime() );
			var maxDateTime = new Date( currentDateTime.getTime() );

			// minimum time
			var timeex = atimes[0].split(':');
			minDateTime.setHours( parseInt( timeex[0] ), parseInt( timeex[1] ) );

			if( currentDateTime < minDateTime ) {
				formattedDate = moment( minDateTime ).format( datepickeropts.format );
				$input.datetimepicker( { value: formattedDate } );
			}

			// maximum time
			timeex = atimes[ atimes.length - 1 ].split(':');
			maxDateTime.setHours( parseInt( timeex[0] ), parseInt( timeex[1] ) );

			if( currentDateTime > maxDateTime ) {
				formattedDate = moment( maxDateTime ).format( datepickeropts.format );
				$input.datetimepicker( { value: formattedDate } );
			}
		};
	}

	var opts = {
		i18n: datepickeropts.i18n,
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
		if ( datepickeropts.value !== '' ) {
			opts.minDate = datepickeropts.value;
		} else {
			opts.minDate = 0;
		}
	}

	if( datepickeropts.max_date !== '' ){
		opts.maxDate = datepickeropts.max_date;
	}

	if( datepickeropts.disabled_days !== ''){
		opts.disabledWeekDays = datepickeropts.disabled_days;
	}

	if( datepickeropts.disabled_calendar_days !== ''){
		opts.disabledDates = datepickeropts.disabled_calendar_days;
	}

	if( datepickeropts.allowed_times !== ''){
		opts.allowTimes = datepickeropts.allowed_times;
	}

	jQuery(datepickeropts.selector).datetimepicker( opts );
	jQuery.datetimepicker.setLocale(datepickeropts.locale);

}
