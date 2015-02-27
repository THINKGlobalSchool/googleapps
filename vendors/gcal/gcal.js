(function($) {
	$.fullCalendar.spotgcalFeed = function(feedId, options) {
		var feedUrl = elgg.get_site_url() + 'calendar/load';
		options = options || {};
		return function(start, end, callback) {
			var params = {
				'start_date': $.fullCalendar.formatDate(start, 'u'),
				'end_date': $.fullCalendar.formatDate(end, 'u'),
				'class_name': options.className
			};
			$.getJSON(feedUrl + "?id=" + feedId, params, function(data) {
				callback(data);
			});
		}
	}
})(jQuery);
