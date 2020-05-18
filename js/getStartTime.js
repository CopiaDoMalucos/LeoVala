// ***********************************
//  Author:  Will S.                 *
//  Date:    2012-06-11              *
//  Updated: 2012-06-20              *
//  Name:    getStartTime.js         *
// ***********************************

function getStartTime(st, tz)
{
	// Parse Date
	var date = parseDate(st);
	
	// Fix Time Zone + Daylight Saving Time
	var now = new Date();
    	var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
    	var nd = new Date(utc + (3600000*(parseInt(tz)+getDST())));

	// Date difference
	var dateDiff = (date-nd);

	if (dateDiff <= 0) {
		// Countdown is over
		return '00:00:00:00';
	} else {
		// Calculate days, hours, minutes, seconds
		var days = Math.floor(dateDiff/(1000*60*60*24));
		var r = dateDiff - (days*1000*60*60*24);
		var hours = Math.floor(r/(1000*60*60));
		r = r - (hours*1000*60*60);
		var minutes = Math.floor(r/(1000*60));
		r = r - (minutes*1000*60);
		var seconds = Math.floor(r/1000);

		// Return dd:hh:mm:ss
		return (	
			fixZero(days) + ':' + 
			fixZero(hours) + ':' + 
			fixZero(minutes) + ':' + 
			fixZero(seconds)
		);
	}
}

function parseDate(st)
{
	// Parse startTime
	var d = st.split(" ")[0];
	var t = st.split(" ")[1];
	
	var date = new Date(
		d.split("/")[2],   // Year
		d.split("/")[1]-1, // Month (zero based)
		d.split("/")[0],   // Day
		t.split(":")[0],   // Hour
		t.split(":")[1],   // Minute
		t.split(":")[2]    // Second
	);
	
	return date;
}

function fixZero(x)
{
	if (x.toString().length == 1) {
		return '0' + x.toString();
	} else {
		return x.toString();
	}
}

function getDST()
{
	var today = new Date();
	var yr = today.getFullYear();
	var jan = new Date(yr,0);	// January 1
	var jul = new Date(yr,6);	// July 1
	// northern hemisphere
	if (jan.getTimezoneOffset() > jul.getTimezoneOffset() && today.getTimezoneOffset() != jan.getTimezoneOffset()){
		return 1;
		}
	// southern hemisphere
	if (jan.getTimezoneOffset() < jul.getTimezoneOffset() && today.getTimezoneOffset() != jul.getTimezoneOffset()){
		return 1;
		}
	// if we reach this point, DST is not in effect on the client computer.	
	return 0;
}