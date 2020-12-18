/**
 * Get the date we just clicked on (td cell)
 *
 * @param {Object} $td The jQuery td element
 */
var getDateFromTdCell = function($td)
{

    var self = this;

    var $tr = $td.parents('tr');

    var date_obj = self.el.fullCalendar('getDate');

    var current_date = parseInt($td.find('.fc-day-number').text());
    var current_month = date_obj.getMonth();
    var current_year = date_obj.getFullYear();

    // We might click on a date from a previous month or next month (grey'd out)
    var is_previous_month = ($tr.hasClass('fc-first') && $td.hasClass('fc-other-month'));
    var is_next_month = (!is_previous_month && $tr.hasClass('fc-last') && $td.hasClass('fc-other-month'));

    if(is_previous_month)
    {
        // If we're on January and we went to a previous month
        if(current_month == 0)
        {
            current_month = 11; // December
            current_year--;
        }
        else
        {
            current_month--;
        }
    }
    else if(is_next_month)
    {
        if(current_month == 11)
        {
            current_month = 0;
            current_year++;
        }
        else
        {
            current_month++;
        }
    }

    var final_date = new Date(current_year, current_month, current_date);

    return final_date;
    
}