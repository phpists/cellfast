function isNaturalNumber(n) {
    n = n.toString();
    var n1 = Math.abs(n),
        n2 = parseInt(n, 10);
    return !isNaN(n1) && n2 === n1 && n1.toString() === n;
}

$('body').on('click', '.plusser__plus', function(event) {
	var th = $(this),
		par = th.closest('.plusser'),
		min = parseInt(par.attr('data-min')),
		max = parseInt(par.attr('data-max')),
		input = par.find('.plusser__input');

	var curVal = parseInt(input.val());
	if (curVal + 1 <= max) {
		input.val(curVal + 1);
	}
	par.trigger('plusser-change');

	event.preventDefault();
});

$('body').on('click', '.plusser__minus', function(event) {
	var th = $(this),
		par = th.closest('.plusser'),
		min = parseInt(par.attr('data-min')),
		max = parseInt(par.attr('data-max')),
		input = par.find('.plusser__input');

	var curVal = parseInt(input.val());
	if (curVal - 1 >= min) {
		input.val(curVal - 1);
	}
	par.trigger('plusser-change');
		
	event.preventDefault();
});

$('body').on('change', '.plusser__input', function(event) {
	var th = $(this),
		par = th.closest('.plusser'),
		min = parseInt(par.attr('data-min')),
		max = parseInt(par.attr('data-max')),
		input = par.find('.plusser__input');

	var that = $(this),
		curVal = that.val();

	if (isNaturalNumber(curVal)) {
		if (parseInt(curVal) >= min && parseInt(curVal) <= max) {
			that.val(curVal);
		} else if (parseInt(curVal) >= min && parseInt(curVal) > max) {
			that.val(max);
		} else {
			that.val(max);
		}
	} else {
		that.val(min);
	}

	par.trigger('plusser-change');

});

// $('.plusser').each(function(index, el) {
// 	var th = $(this),
// 		min = parseInt(th.attr('data-min')),
// 		max = parseInt(th.attr('data-max')),
// 		input = th.find('.plusser__input'),
// 		plusBtn = th.find('.plusser__plus'),
// 		minusBtn = th.find('.plusser__minus');

	// plusBtn.on('click', function(event) {
	// 	var curVal = parseInt(input.val());
	// 	if (curVal + 1 <= max) {
	// 		input.val(curVal + 1);
	// 	}
	// 	th.trigger('plusser-change');
	// 	event.preventDefault();
	// });

	// minusBtn.on('click', function(event) {
	// 	var curVal = parseInt(input.val());
	// 	if (curVal - 1 >= min) {
	// 		input.val(curVal - 1);
	// 	}
	// 	th.trigger('plusser-change');
	// 	event.preventDefault();
	// });

	// input.on('change', function() {
	// 	var that = $(this),
	// 		curVal = that.val();

	// 	if (isNaturalNumber(curVal)) {
	// 		if (parseInt(curVal) >= min && parseInt(curVal) <= max) {
	// 			that.val(curVal);
	// 		} else if (parseInt(curVal) >= min && parseInt(curVal) > max) {
	// 			that.val(max);
	// 		} else {
	// 			that.val(max);
	// 		}
	// 	} else {
	// 		that.val(min);
	// 	}

	// 	th.trigger('plusser-change');
	// });
// });