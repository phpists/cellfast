var dateDiffInSeconds;

$('.rtimer').each(function(index, el) {
	var th = $(this),
		finTime = th.attr('data-finish'),
		dayBlock = th.find('.rtimer__d span'), 
		hoursBlock = th.find('.rtimer__h span'), 
		minutesBlock = th.find('.rtimer__m span'), 
		secondsBlock = th.find('.rtimer__s span');

	var momentCurDate =  moment();
	var momentFinishDate = moment(finTime, 'DD.MM.YYYY:hh:mm:ss');

	dateDiffInSeconds = momentFinishDate.diff(momentCurDate);

	if (dateDiffInSeconds > 0) {
		setInterval(function () {
			if (dateDiffInSeconds > 0) {
				var dur =  moment.duration(dateDiffInSeconds);
				if (dur.days() > 9) {
					dayBlock.text(dur.days());
				} else {
					dayBlock.text("0" + dur.days());
				}
				if (dur.hours() > 9) {
					hoursBlock.text(dur.hours());
				} else {
					hoursBlock.text("0" + dur.hours());
				}
				if (dur.minutes() > 9) {
					minutesBlock.text(dur.minutes());
				} else {
					minutesBlock.text("0" + dur.minutes());
				}
				if (dur.seconds() > 9) {
					secondsBlock.text(dur.seconds());
				} else {
					secondsBlock.text("0" + dur.seconds());
				}

				dateDiffInSeconds = dateDiffInSeconds - 1000;
			}
		}, 1000)
	} else {
		dayBlock.text('00');
		hoursBlock.text('00');
		minutesBlock.text('00');
		secondsBlock.text('00');
	}
});