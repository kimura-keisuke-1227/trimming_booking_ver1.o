<?php
namespace app\classes;

class BookingsCalc
{
    public static function getAllBookingsByDate($bookings , $date){
        $dateBookings = $bookings 
            -> where('date' , $date) 
            -> sortBy('st_time');
        return $dateBookings;
     }
}