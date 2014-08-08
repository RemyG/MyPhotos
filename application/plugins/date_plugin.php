<?php

function getDateFromTimestamp($timestamp) {
	
	return date('F d, Y', $timestamp);
	
}

function getDateAsYYYYMMDDFromTimestamp($timestamp) {

	return date('YMd', $timestamp);

}

function getDateAsDDFromTimestamp($timestamp) {

	return date('d', $timestamp);

}

function getDateAsMMFromTimestamp($timestamp) {

	return date('m', $timestamp);

}

function getDateAsYYYYFromTimestamp($timestamp) {

	return date('Y', $timestamp);

}

function getTimeFromTimestamp($timestamp) {
	
	return date('H:i', $timestamp);
	
}

function getTimestampFromDateAsYYYYMMDD($date) {
	
	$month = substr($date, 4, 2);
	$day = substr($date, 6, 2);
	$year = substr($date, 0, 4);
	if(checkdate($month, $day, $year)) {
		return mktime(12, 00, 00, $month, $day, $year);
	}
	return false;
	
}