<?php

namespace App\ClassPerso;

use DateInterval;
use DatePeriod;
use DateTime;
use function dump;

/**
 * Description of DateInter
 *
 * @author jrobert
 */
class DateClass {

    private $debut;
    private $fin;
    private $unite;
    private $days = ['Monday'=>'Lundi', 'Tuesday'=>'Mardi', 'Wednesday'=>'Mercredi', 'Thursday'=>'Jeudi', 'Friday'=>'Vendredi', 'Saturday'=>'Samedi', 'Sunday'=>'Dimanche'];
    private $jours = array();
    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    private $month;
    private $year;
    private $translate_month = ['January'=>'Janvier', 'February'=>'Février', 'March'=>'Mars', 'April'=>'Avril', 'May'=>'Mai', 'June'=>'Juin', 'July'=>'Juillet', 'August'=>'Aout', 'September'=>'Septembre', 'October'=>'Octobre', 'November'=>'Novembre', 'December'=>'Décembre'];

    public function __construct(int $month = null, int $year = null) {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }
        if ($year === null) {
            $year = intval(date('Y'));
        }
        if ($month !== 12) {
            $month = $month % 12;
        }
        $this->month = $month;
        $this->year = $year;
    }

    public function __toString(): string {
        return $this->months[$this->month - 1] . ' ' . $this->year;
    }

    public function getWeeks(): int {
        $start = DateClass::getPremierJour();
        $end = (clone $start)->modify('+1 month -1 day');
        $weeks = intval($end->format('W')) - intval($start->format('W')) + 1;
        if ($weeks < 0) {
//            $weeks = intval($end->modify('number week')) - intval($start->modify('number week')) + 1;
            $weeks = 0;
            $start = DateClass::getPremierJour()->modify('last monday');
            $end = $end->modify('next sunday');
            $period = new DatePeriod($start, new DateInterval('P1D'), $end);
            foreach ($period as $day) {
                if ($day->format('D') == 'Mon') {
                    $weeks++;
                }
            }
        }
        return $weeks;
    }

    public function getPremierJour(): DateTime {
        return new DateTime("{$this->year}-{$this->month}-01");
    }

    public function getJours(): array {
        $this->jours = array();
        $start = DateClass::getPremierJour();
        $end = (clone $start)->modify('+1 month -1 day');
        $start = $start->modify('last monday');
        $end = $end->modify('next sunday +1 day');
        $this->debut=$start;
        $this->fin=$end;
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        foreach ($period as $day) {
            $this->jours[]=$day;
        }
        return $this->jours;
    }

//    public function parcourMois($interval){
//        $dateDeb = date_create('2019-12-25');
//        $dateFin = date_create();
//        foreach(){
//            
//        }
//    }

    public function withinMonth(DateTime $date): bool {
        return $this->getPremierJour()->format('Y-m') === $date->format('Y-m');
    }

    public function nextMonth(): DateClass {
        $month = $this->month + 1;
        $year = $this->year;
        if ($month > 12) {
            $month = 1;
            $year += 1;
        }
        return new DateClass($month, $year);
    }

    public function previousMonth(): DateClass {
        $month = $this->month - 1;
        $year = $this->year;
        if ($month < 1) {
            $month = 12;
            $year -= 1;
        }
        return new DateClass($month, $year);
    }

    function getDebut() {
        return $this->debut;
    }

    function getFin() {
        return $this->fin;
    }

    function getUnite() {
        return $this->unite;
    }

    function setDebut($debut) {
        $this->debut = $debut;
    }

    function setFin($fin) {
        $this->fin = $fin;
    }

    function setUnite($unite) {
        $this->unite = $unite;
    }

    function getDays() {
        return $this->days;
    }

    function getMonths() {
        return $this->months;
    }

    function getMonth() {
        return $this->month;
    }

    function getYear() {
        return $this->year;
    }

    function setDays($days) {
        $this->days = $days;
    }

    function setMonths($months) {
        $this->months = $months;
    }

    function setMonth($month) {
        $this->month = $month;
    }

    function setYear($year) {
        $this->year = $year;
    }

    function getTranslate_month() {
        return $this->translate_month;
    }

    function setTranslate_month($translate_month) {
        $this->translate_month = $translate_month;
    }


}
