<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing various cron expressions for scheduling tasks.
 * Each value is a cron expression string that defines the scheduling pattern.
 *
 * @method static EVERY_SECOND() Returns the EVERY_SECOND enum instance
 * @method static EVERY_5_SECONDS() Returns the EVERY_5_SECONDS enum instance
 * @method static EVERY_10_SECONDS() Returns the EVERY_10_SECONDS enum instance
 * @method static EVERY_30_SECONDS() Returns the EVERY_30_SECONDS enum instance
 * @method static EVERY_MINUTE() Returns the EVERY_MINUTE enum instance
 * @method static EVERY_5_MINUTES() Returns the EVERY_5_MINUTES enum instance
 * @method static EVERY_10_MINUTES() Returns the EVERY_10_MINUTES enum instance
 * @method static EVERY_30_MINUTES() Returns the EVERY_30_MINUTES enum instance
 * @method static EVERY_HOUR() Returns the EVERY_HOUR enum instance
 * @method static EVERY_2_HOURS() Returns the EVERY_2_HOURS enum instance
 * @method static EVERY_3_HOURS() Returns the EVERY_3_HOURS enum instance
 * @method static EVERY_4_HOURS() Returns the EVERY_4_HOURS enum instance
 * @method static EVERY_5_HOURS() Returns the EVERY_5_HOURS enum instance
 * @method static EVERY_6_HOURS() Returns the EVERY_6_HOURS enum instance
 * @method static EVERY_7_HOURS() Returns the EVERY_7_HOURS enum instance
 * @method static EVERY_8_HOURS() Returns the EVERY_8_HOURS enum instance
 * @method static EVERY_9_HOURS() Returns the EVERY_9_HOURS enum instance
 * @method static EVERY_10_HOURS() Returns the EVERY_10_HOURS enum instance
 * @method static EVERY_11_HOURS() Returns the EVERY_11_HOURS enum instance
 * @method static EVERY_12_HOURS() Returns the EVERY_12_HOURS enum instance
 * @method static EVERY_DAY_AT_1AM() Returns the EVERY_DAY_AT_1AM enum instance
 * @method static EVERY_DAY_AT_2AM() Returns the EVERY_DAY_AT_2AM enum instance
 * @method static EVERY_DAY_AT_3AM() Returns the EVERY_DAY_AT_3AM enum instance
 * @method static EVERY_DAY_AT_4AM() Returns the EVERY_DAY_AT_4AM enum instance
 * @method static EVERY_DAY_AT_5AM() Returns the EVERY_DAY_AT_5AM enum instance
 * @method static EVERY_DAY_AT_6AM() Returns the EVERY_DAY_AT_6AM enum instance
 * @method static EVERY_DAY_AT_7AM() Returns the EVERY_DAY_AT_7AM enum instance
 * @method static EVERY_DAY_AT_8AM() Returns the EVERY_DAY_AT_8AM enum instance
 * @method static EVERY_DAY_AT_9AM() Returns the EVERY_DAY_AT_9AM enum instance
 * @method static EVERY_DAY_AT_10AM() Returns the EVERY_DAY_AT_10AM enum instance
 * @method static EVERY_DAY_AT_11AM() Returns the EVERY_DAY_AT_11AM enum instance
 * @method static EVERY_DAY_AT_NOON() Returns the EVERY_DAY_AT_NOON enum instance
 * @method static EVERY_DAY_AT_1PM() Returns the EVERY_DAY_AT_1PM enum instance
 * @method static EVERY_DAY_AT_2PM() Returns the EVERY_DAY_AT_2PM enum instance
 * @method static EVERY_DAY_AT_3PM() Returns the EVERY_DAY_AT_3PM enum instance
 * @method static EVERY_DAY_AT_4PM() Returns the EVERY_DAY_AT_4PM enum instance
 * @method static EVERY_DAY_AT_5PM() Returns the EVERY_DAY_AT_5PM enum instance
 * @method static EVERY_DAY_AT_6PM() Returns the EVERY_DAY_AT_6PM enum instance
 * @method static EVERY_DAY_AT_7PM() Returns the EVERY_DAY_AT_7PM enum instance
 * @method static EVERY_DAY_AT_8PM() Returns the EVERY_DAY_AT_8PM enum instance
 * @method static EVERY_DAY_AT_9PM() Returns the EVERY_DAY_AT_9PM enum instance
 * @method static EVERY_DAY_AT_10PM() Returns the EVERY_DAY_AT_10PM enum instance
 * @method static EVERY_DAY_AT_11PM() Returns the EVERY_DAY_AT_11PM enum instance
 * @method static EVERY_DAY_AT_MIDNIGHT() Returns the EVERY_DAY_AT_MIDNIGHT enum instance
 * @method static EVERY_WEEK() Returns the EVERY_WEEK enum instance
 * @method static EVERY_WEEKDAY() Returns the EVERY_WEEKDAY enum instance
 * @method static EVERY_WEEKEND() Returns the EVERY_WEEKEND enum instance
 * @method static EVERY_1ST_DAY_OF_MONTH_AT_MIDNIGHT() Returns the EVERY_1ST_DAY_OF_MONTH_AT_MIDNIGHT enum instance
 * @method static EVERY_1ST_DAY_OF_MONTH_AT_NOON() Returns the EVERY_1ST_DAY_OF_MONTH_AT_NOON enum instance
 * @method static EVERY_2ND_HOUR() Returns the EVERY_2ND_HOUR enum instance
 * @method static EVERY_2ND_HOUR_FROM_1AM_THROUGH_11PM() Returns the EVERY_2ND_HOUR_FROM_1AM_THROUGH_11PM enum instance
 * @method static EVERY_2ND_MONTH() Returns the EVERY_2ND_MONTH enum instance
 * @method static EVERY_QUARTER() Returns the EVERY_QUARTER enum instance
 * @method static EVERY_6_MONTHS() Returns the EVERY_6_MONTHS enum instance
 * @method static EVERY_YEAR() Returns the EVERY_YEAR enum instance
 * @method static EVERY_30_MINUTES_BETWEEN_9AM_AND_5PM() Returns the EVERY_30_MINUTES_BETWEEN_9AM_AND_5PM enum instance
 * @method static EVERY_30_MINUTES_BETWEEN_9AM_AND_6PM() Returns the EVERY_30_MINUTES_BETWEEN_9AM_AND_6PM enum instance
 * @method static EVERY_30_MINUTES_BETWEEN_10AM_AND_7PM() Returns the EVERY_30_MINUTES_BETWEEN_10AM_AND_7PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_1AM() Returns the MONDAY_TO_FRIDAY_AT_1AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_2AM() Returns the MONDAY_TO_FRIDAY_AT_2AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_3AM() Returns the MONDAY_TO_FRIDAY_AT_3AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_4AM() Returns the MONDAY_TO_FRIDAY_AT_4AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_5AM() Returns the MONDAY_TO_FRIDAY_AT_5AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_6AM() Returns the MONDAY_TO_FRIDAY_AT_6AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_7AM() Returns the MONDAY_TO_FRIDAY_AT_7AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_8AM() Returns the MONDAY_TO_FRIDAY_AT_8AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_9AM() Returns the MONDAY_TO_FRIDAY_AT_9AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_10AM() Returns the MONDAY_TO_FRIDAY_AT_10AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_11AM() Returns the MONDAY_TO_FRIDAY_AT_11AM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_NOON() Returns the MONDAY_TO_FRIDAY_AT_NOON enum instance
 * @method static MONDAY_TO_FRIDAY_AT_1PM() Returns the MONDAY_TO_FRIDAY_AT_1PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_2PM() Returns the MONDAY_TO_FRIDAY_AT_2PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_3PM() Returns the MONDAY_TO_FRIDAY_AT_3PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_4PM() Returns the MONDAY_TO_FRIDAY_AT_4PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_5PM() Returns the MONDAY_TO_FRIDAY_AT_5PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_6PM() Returns the MONDAY_TO_FRIDAY_AT_6PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_7PM() Returns the MONDAY_TO_FRIDAY_AT_7PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_8PM() Returns the MONDAY_TO_FRIDAY_AT_8PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_9PM() Returns the MONDAY_TO_FRIDAY_AT_9PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_10PM() Returns the MONDAY_TO_FRIDAY_AT_10PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_11PM() Returns the MONDAY_TO_FRIDAY_AT_11PM enum instance
 * @method static MONDAY_TO_FRIDAY_AT_MIDNIGHT() Returns the MONDAY_TO_FRIDAY_AT_MIDNIGHT enum instance
 * @method static EVERY_SATURDAY_AT_MIDNIGHT() Returns the EVERY_SATURDAY_AT_MIDNIGHT enum instance
 * @method static EVERY_SUNDAY_AT_MIDNIGHT() Returns the EVERY_SUNDAY_AT_MIDNIGHT enum instance
 * @method static EVERY_WEEKEND_AT_MIDNIGHT() Returns the EVERY_WEEKEND_AT_MIDNIGHT enum instance
 * @method static EVERY_DAY_AT_2_30PM() Returns the EVERY_DAY_AT_2_30PM enum instance
 * @method static EVERY_WEEKDAY_AT_2_30PM() Returns the EVERY_WEEKDAY_AT_2_30PM enum instance
 * @method static EVERY_WEEKDAY_AT_8_45AM() Returns the EVERY_WEEKDAY_AT_8_45AM enum instance
 * @method static EVERY_15TH_DAY_OF_MONTH_AT_3PM() Returns the EVERY_15TH_DAY_OF_MONTH_AT_3PM enum instance
 * @method static EVERY_HOUR_BETWEEN_8AM_AND_6PM_ON_WEEKDAYS() Returns the EVERY_HOUR_BETWEEN_8AM_AND_6PM_ON_WEEKDAYS enum instance
 * @method static EVERY_15_MINUTES() Returns the EVERY_15_MINUTES enum instance
 * @method static EVERY_20_MINUTES() Returns the EVERY_20_MINUTES enum instance
 * @method static EVERY_45_MINUTES() Returns the EVERY_45_MINUTES enum instance
 */
enum CronExpression: string
{
    use Enum;

    /**
     * Executes every second.
     */
    #[Label('Every Second')]
    #[Description('Cron expression for executing a task every second.')]
    case EVERY_SECOND = '* * * * *';

    /**
     * Executes every 5 seconds.
     */
    #[Label('Every 5 Seconds')]
    #[Description('Cron expression for executing a task every 5 seconds.')]
    case EVERY_5_SECONDS = '*/5 * * * *';

    /**
     * Executes every 10 seconds.
     */
    #[Label('Every 10 Seconds')]
    #[Description('Cron expression for executing a task every 10 seconds.')]
    case EVERY_10_SECONDS = '*/10 * * * *';

    /**
     * Executes every 30 seconds.
     */
    #[Label('Every 30 Seconds')]
    #[Description('Cron expression for executing a task every 30 seconds.')]
    case EVERY_30_SECONDS = '*/30 * * * *';

    /**
     * Executes every minute.
     */
    #[Label('Every Minute')]
    #[Description('Cron expression for executing a task every minute.')]
    case EVERY_MINUTE = '*/1 * * *';

    /**
     * Executes every 5 minutes.
     */
    #[Label('Every 5 Minutes')]
    #[Description('Cron expression for executing a task every 5 minutes.')]
    case EVERY_5_MINUTES = '0 */5 * * *';

    /**
     * Executes every 10 minutes.
     */
    #[Label('Every 10 Minutes')]
    #[Description('Cron expression for executing a task every 10 minutes.')]
    case EVERY_10_MINUTES = '0 */10 * * *';

    /**
     * Executes every 30 minutes.
     */
    #[Label('Every 30 Minutes')]
    #[Description('Cron expression for executing a task every 30 minutes.')]
    case EVERY_30_MINUTES = '0 */30 * * *';

    /**
     * Executes every hour.
     */
    #[Label('Every Hour')]
    #[Description('Cron expression for executing a task every hour.')]
    case EVERY_HOUR = '0 0-23/1 * *';

    /**
     * Executes every 2 hours.
     */
    #[Label('Every 2 Hours')]
    #[Description('Cron expression for executing a task every 2 hours.')]
    case EVERY_2_HOURS = '0 0-23/2 * *';

    /**
     * Executes every 3 hours.
     */
    #[Label('Every 3 Hours')]
    #[Description('Cron expression for executing a task every 3 hours.')]
    case EVERY_3_HOURS = '0 0-23/3 * *';

    /**
     * Executes every 4 hours.
     */
    #[Label('Every 4 Hours')]
    #[Description('Cron expression for executing a task every 4 hours.')]
    case EVERY_4_HOURS = '0 0-23/4 * *';

    /**
     * Executes every 5 hours.
     */
    #[Label('Every 5 Hours')]
    #[Description('Cron expression for executing a task every 5 hours.')]
    case EVERY_5_HOURS = '0 0-23/5 * *';

    /**
     * Executes every 6 hours.
     */
    #[Label('Every 6 Hours')]
    #[Description('Cron expression for executing a task every 6 hours.')]
    case EVERY_6_HOURS = '0 0-23/6 * *';

    /**
     * Executes every 7 hours.
     */
    #[Label('Every 7 Hours')]
    #[Description('Cron expression for executing a task every 7 hours.')]
    case EVERY_7_HOURS = '0 0-23/7 * *';

    /**
     * Executes every 8 hours.
     */
    #[Label('Every 8 Hours')]
    #[Description('Cron expression for executing a task every 8 hours.')]
    case EVERY_8_HOURS = '0 0-23/8 * *';

    /**
     * Executes every 9 hours.
     */
    #[Label('Every 9 Hours')]
    #[Description('Cron expression for executing a task every 9 hours.')]
    case EVERY_9_HOURS = '0 0-23/9 * *';

    /**
     * Executes every 10 hours.
     */
    #[Label('Every 10 Hours')]
    #[Description('Cron expression for executing a task every 10 hours.')]
    case EVERY_10_HOURS = '0 0-23/10 * *';

    /**
     * Executes every 11 hours.
     */
    #[Label('Every 11 Hours')]
    #[Description('Cron expression for executing a task every 11 hours.')]
    case EVERY_11_HOURS = '0 0-23/11 * *';

    /**
     * Executes every 12 hours.
     */
    #[Label('Every 12 Hours')]
    #[Description('Cron expression for executing a task every 12 hours.')]
    case EVERY_12_HOURS = '0 0-23/12 * *';

    /**
     * Executes every day at 1 AM.
     */
    #[Label('Every Day at 1 AM')]
    #[Description('Cron expression for executing a task every day at 1 AM.')]
    case EVERY_DAY_AT_1AM = '0 01 * *';

    /**
     * Executes every day at 2 AM.
     */
    #[Label('Every Day at 2 AM')]
    #[Description('Cron expression for executing a task every day at 2 AM.')]
    case EVERY_DAY_AT_2AM = '0 02 * *';

    /**
     * Executes every day at 3 AM.
     */
    #[Label('Every Day at 3 AM')]
    #[Description('Cron expression for executing a task every day at 3 AM.')]
    case EVERY_DAY_AT_3AM = '0 03 * *';

    /**
     * Executes every day at 4 AM.
     */
    #[Label('Every Day at 4 AM')]
    #[Description('Cron expression for executing a task every day at 4 AM.')]
    case EVERY_DAY_AT_4AM = '0 04 * *';

    /**
     * Executes every day at 5 AM.
     */
    #[Label('Every Day at 5 AM')]
    #[Description('Cron expression for executing a task every day at 5 AM.')]
    case EVERY_DAY_AT_5AM = '0 05 * *';

    /**
     * Executes every day at 6 AM.
     */
    #[Label('Every Day at 6 AM')]
    #[Description('Cron expression for executing a task every day at 6 AM.')]
    case EVERY_DAY_AT_6AM = '0 06 * *';

    /**
     * Executes every day at 7 AM.
     */
    #[Label('Every Day at 7 AM')]
    #[Description('Cron expression for executing a task every day at 7 AM.')]
    case EVERY_DAY_AT_7AM = '0 07 * *';

    /**
     * Executes every day at 8 AM.
     */
    #[Label('Every Day at 8 AM')]
    #[Description('Cron expression for executing a task every day at 8 AM.')]
    case EVERY_DAY_AT_8AM = '0 08 * *';

    /**
     * Executes every day at 9 AM.
     */
    #[Label('Every Day at 9 AM')]
    #[Description('Cron expression for executing a task every day at 9 AM.')]
    case EVERY_DAY_AT_9AM = '0 09 * *';

    /**
     * Executes every day at 10 AM.
     */
    #[Label('Every Day at 10 AM')]
    #[Description('Cron expression for executing a task every day at 10 AM.')]
    case EVERY_DAY_AT_10AM = '0 10 * *';

    /**
     * Executes every day at 11 AM.
     */
    #[Label('Every Day at 11 AM')]
    #[Description('Cron expression for executing a task every day at 11 AM.')]
    case EVERY_DAY_AT_11AM = '0 11 * *';

    /**
     * Executes every day at noon (12 PM).
     */
    #[Label('Every Day at Noon')]
    #[Description('Cron expression for executing a task every day at noon (12 PM).')]
    case EVERY_DAY_AT_NOON = '0 12 * *';

    /**
     * Executes every day at 1 PM.
     */
    #[Label('Every Day at 1 PM')]
    #[Description('Cron expression for executing a task every day at 1 PM.')]
    case EVERY_DAY_AT_1PM = '0 13 * *';

    /**
     * Executes every day at 2 PM.
     */
    #[Label('Every Day at 2 PM')]
    #[Description('Cron expression for executing a task every day at 2 PM.')]
    case EVERY_DAY_AT_2PM = '0 14 * *';

    /**
     * Executes every day at 3 PM.
     */
    #[Label('Every Day at 3 PM')]
    #[Description('Cron expression for executing a task every day at 3 PM.')]
    case EVERY_DAY_AT_3PM = '0 15 * *';

    /**
     * Executes every day at 4 PM.
     */
    #[Label('Every Day at 4 PM')]
    #[Description('Cron expression for executing a task every day at 4 PM.')]
    case EVERY_DAY_AT_4PM = '0 16 * *';

    /**
     * Executes every day at 5 PM.
     */
    #[Label('Every Day at 5 PM')]
    #[Description('Cron expression for executing a task every day at 5 PM.')]
    case EVERY_DAY_AT_5PM = '0 17 * *';

    /**
     * Executes every day at 6 PM.
     */
    #[Label('Every Day at 6 PM')]
    #[Description('Cron expression for executing a task every day at 6 PM.')]
    case EVERY_DAY_AT_6PM = '0 18 * *';

    /**
     * Executes every day at 7 PM.
     */
    #[Label('Every Day at 7 PM')]
    #[Description('Cron expression for executing a task every day at 7 PM.')]
    case EVERY_DAY_AT_7PM = '0 19 * *';

    /**
     * Executes every day at 8 PM.
     */
    #[Label('Every Day at 8 PM')]
    #[Description('Cron expression for executing a task every day at 8 PM.')]
    case EVERY_DAY_AT_8PM = '0 20 * *';

    /**
     * Executes every day at 9 PM.
     */
    #[Label('Every Day at 9 PM')]
    #[Description('Cron expression for executing a task every day at 9 PM.')]
    case EVERY_DAY_AT_9PM = '0 21 * *';

    /**
     * Executes every day at 10 PM.
     */
    #[Label('Every Day at 10 PM')]
    #[Description('Cron expression for executing a task every day at 10 PM.')]
    case EVERY_DAY_AT_10PM = '0 22 * *';

    /**
     * Executes every day at 11 PM.
     */
    #[Label('Every Day at 11 PM')]
    #[Description('Cron expression for executing a task every day at 11 PM.')]
    case EVERY_DAY_AT_11PM = '0 23 * *';

    /**
     * Executes every day at midnight (12 AM).
     */
    #[Label('Every Day at Midnight')]
    #[Description('Cron expression for executing a task every day at midnight (12 AM).')]
    case EVERY_DAY_AT_MIDNIGHT = '0 0 * *';

    /**
     * Executes every week on Sunday at midnight.
     */
    #[Label('Every Week')]
    #[Description('Cron expression for executing a task every week on Sunday at midnight.')]
    case EVERY_WEEK = '0 0 * * 0';

    /**
     * Executes every weekday (Monday to Friday) at midnight.
     */
    #[Label('Every Weekday')]
    #[Description('Cron expression for executing a task every weekday (Monday to Friday) at midnight.')]
    case EVERY_WEEKDAY = '0 0 * * 1-5';

    /**
     * Executes every weekend (Saturday and Sunday) at midnight.
     */
    #[Label('Every Weekend')]
    #[Description('Cron expression for executing a task every weekend (Saturday and Sunday) at midnight.')]
    case EVERY_WEEKEND = '0 0 * * 6,0';

    /**
     * Executes on the 1st day of every month at midnight.
     */
    #[Label('1st Day of Month at Midnight')]
    #[Description('Cron expression for executing a task on the 1st day of every month at midnight.')]
    case EVERY_1ST_DAY_OF_MONTH_AT_MIDNIGHT = '0 0 1 *';

    /**
     * Executes on the 1st day of every month at noon.
     */
    #[Label('1st Day of Month at Noon')]
    #[Description('Cron expression for executing a task on the 1st day of every month at noon.')]
    case EVERY_1ST_DAY_OF_MONTH_AT_NOON = '0 12 1 *';

    /**
     * Executes every 2 hours.
     */
    #[Label('Every 2nd Hour')]
    #[Description('Cron expression for executing a task every 2 hours.')]
    case EVERY_2ND_HOUR = '0 */2 * *';

    /**
     * Executes every 2nd hour from 1 AM through 11 PM.
     */
    #[Label('Every 2nd Hour from 1 AM to 11 PM')]
    #[Description('Cron expression for executing a task every 2nd hour from 1 AM through 11 PM.')]
    case EVERY_2ND_HOUR_FROM_1AM_THROUGH_11PM = '0 1-23/2 * *';

    /**
     * Executes on the 1st day of every 2nd month at midnight.
     */
    #[Label('Every 2nd Month')]
    #[Description('Cron expression for executing a task on the 1st day of every 2nd month at midnight.')]
    case EVERY_2ND_MONTH = '0 0 1 */2';

    /**
     * Executes on the 1st day of every quarter (3 months) at midnight.
     */
    #[Label('Every Quarter')]
    #[Description('Cron expression for executing a task on the 1st day of every quarter (3 months) at midnight.')]
    case EVERY_QUARTER = '0 0 1 */3';

    /**
     * Executes on the 1st day of every 6 months at midnight.
     */
    #[Label('Every 6 Months')]
    #[Description('Cron expression for executing a task on the 1st day of every 6 months at midnight.')]
    case EVERY_6_MONTHS = '0 0 1 */6';

    /**
     * Executes on the 1st day of January at midnight.
     */
    #[Label('Every Year')]
    #[Description('Cron expression for executing a task on the 1st day of January at midnight.')]
    case EVERY_YEAR = '0 0 1 1';

    /**
     * Executes every 30 minutes between 9 AM and 5 PM.
     */
    #[Label('Every 30 Minutes Between 9 AM and 5 PM')]
    #[Description('Cron expression for executing a task every 30 minutes between 9 AM and 5 PM.')]
    case EVERY_30_MINUTES_BETWEEN_9AM_AND_5PM = '0 */30 9-17 * *';

    /**
     * Executes every 30 minutes between 9 AM and 6 PM.
     */
    #[Label('Every 30 Minutes Between 9 AM and 6 PM')]
    #[Description('Cron expression for executing a task every 30 minutes between 9 AM and 6 PM.')]
    case EVERY_30_MINUTES_BETWEEN_9AM_AND_6PM = '0 */30 9-18 * *';

    /**
     * Executes every 30 minutes between 10 AM and 7 PM.
     */
    #[Label('Every 30 Minutes Between 10 AM and 7 PM')]
    #[Description('Cron expression for executing a task every 30 minutes between 10 AM and 7 PM.')]
    case EVERY_30_MINUTES_BETWEEN_10AM_AND_7PM = '0 */30 10-19 * *';

    /**
     * Executes Monday to Friday at 1 AM.
     */
    #[Label('Monday to Friday at 1 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 1 AM.')]
    case MONDAY_TO_FRIDAY_AT_1AM = '0 0 01 * * 1-5';

    /**
     * Executes Monday to Friday at 2 AM.
     */
    #[Label('Monday to Friday at 2 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 2 AM.')]
    case MONDAY_TO_FRIDAY_AT_2AM = '0 0 02 * * 1-5';

    /**
     * Executes Monday to Friday at 3 AM.
     */
    #[Label('Monday to Friday at 3 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 3 AM.')]
    case MONDAY_TO_FRIDAY_AT_3AM = '0 0 03 * * 1-5';

    /**
     * Executes Monday to Friday at 4 AM.
     */
    #[Label('Monday to Friday at 4 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 4 AM.')]
    case MONDAY_TO_FRIDAY_AT_4AM = '0 0 04 * * 1-5';

    /**
     * Executes Monday to Friday at 5 AM.
     */
    #[Label('Monday to Friday at 5 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 5 AM.')]
    case MONDAY_TO_FRIDAY_AT_5AM = '0 0 05 * * 1-5';

    /**
     * Executes Monday to Friday at 6 AM.
     */
    #[Label('Monday to Friday at 6 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 6 AM.')]
    case MONDAY_TO_FRIDAY_AT_6AM = '0 0 06 * * 1-5';

    /**
     * Executes Monday to Friday at 7 AM.
     */
    #[Label('Monday to Friday at 7 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 7 AM.')]
    case MONDAY_TO_FRIDAY_AT_7AM = '0 0 07 * * 1-5';

    /**
     * Executes Monday to Friday at 8 AM.
     */
    #[Label('Monday to Friday at 8 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 8 AM.')]
    case MONDAY_TO_FRIDAY_AT_8AM = '0 0 08 * * 1-5';

    /**
     * Executes Monday to Friday at 9 AM.
     */
    #[Label('Monday to Friday at 9 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 9 AM.')]
    case MONDAY_TO_FRIDAY_AT_9AM = '0 0 09 * * 1-5';

    /**
     * Executes Monday to Friday at 10 AM.
     */
    #[Label('Monday to Friday at 10 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 10 AM.')]
    case MONDAY_TO_FRIDAY_AT_10AM = '0 0 10 * * 1-5';

    /**
     * Executes Monday to Friday at 11 AM.
     */
    #[Label('Monday to Friday at 11 AM')]
    #[Description('Cron expression for executing a task Monday to Friday at 11 AM.')]
    case MONDAY_TO_FRIDAY_AT_11AM = '0 0 11 * * 1-5';

    /**
     * Executes Monday to Friday at noon (12 PM).
     */
    #[Label('Monday to Friday at Noon')]
    #[Description('Cron expression for executing a task Monday to Friday at noon (12 PM).')]
    case MONDAY_TO_FRIDAY_AT_NOON = '0 0 12 * * 1-5';

    /**
     * Executes Monday to Friday at 1 PM.
     */
    #[Label('Monday to Friday at 1 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 1 PM.')]
    case MONDAY_TO_FRIDAY_AT_1PM = '0 0 13 * * 1-5';

    /**
     * Executes Monday to Friday at 2 PM.
     */
    #[Label('Monday to Friday at 2 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 2 PM.')]
    case MONDAY_TO_FRIDAY_AT_2PM = '0 0 14 * * 1-5';

    /**
     * Executes Monday to Friday at 3 PM.
     */
    #[Label('Monday to Friday at 3 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 3 PM.')]
    case MONDAY_TO_FRIDAY_AT_3PM = '0 0 15 * * 1-5';

    /**
     * Executes Monday to Friday at 4 PM.
     */
    #[Label('Monday to Friday at 4 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 4 PM.')]
    case MONDAY_TO_FRIDAY_AT_4PM = '0 0 16 * * 1-5';

    /**
     * Executes Monday to Friday at 5 PM.
     */
    #[Label('Monday to Friday at 5 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 5 PM.')]
    case MONDAY_TO_FRIDAY_AT_5PM = '0 0 17 * * 1-5';

    /**
     * Executes Monday to Friday at 6 PM.
     */
    #[Label('Monday to Friday at 6 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 6 PM.')]
    case MONDAY_TO_FRIDAY_AT_6PM = '0 0 18 * * 1-5';

    /**
     * Executes Monday to Friday at 7 PM.
     */
    #[Label('Monday to Friday at 7 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 7 PM.')]
    case MONDAY_TO_FRIDAY_AT_7PM = '0 0 19 * * 1-5';

    /**
     * Executes Monday to Friday at 8 PM.
     */
    #[Label('Monday to Friday at 8 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 8 PM.')]
    case MONDAY_TO_FRIDAY_AT_8PM = '0 0 20 * * 1-5';

    /**
     * Executes Monday to Friday at 9 PM.
     */
    #[Label('Monday to Friday at 9 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 9 PM.')]
    case MONDAY_TO_FRIDAY_AT_9PM = '0 0 21 * * 1-5';

    /**
     * Executes Monday to Friday at 10 PM.
     */
    #[Label('Monday to Friday at 10 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 10 PM.')]
    case MONDAY_TO_FRIDAY_AT_10PM = '0 0 22 * * 1-5';

    /**
     * Executes Monday to Friday at 11 PM.
     */
    #[Label('Monday to Friday at 11 PM')]
    #[Description('Cron expression for executing a task Monday to Friday at 11 PM.')]
    case MONDAY_TO_FRIDAY_AT_11PM = '0 0 23 * * 1-5';

    /**
     * Executes Monday to Friday at midnight (12 AM).
     */
    #[Label('Monday to Friday at Midnight')]
    #[Description('Cron expression for executing a task Monday to Friday at midnight (12 AM).')]
    case MONDAY_TO_FRIDAY_AT_MIDNIGHT = '0 0 0 * * 1-5';

    /**
     * Executes every Saturday at midnight.
     */
    #[Label('Every Saturday at Midnight')]
    #[Description('Cron expression for executing a task every Saturday at midnight.')]
    case EVERY_SATURDAY_AT_MIDNIGHT = '0 0 0 * * 6';

    /**
     * Executes every Sunday at midnight.
     */
    #[Label('Every Sunday at Midnight')]
    #[Description('Cron expression for executing a task every Sunday at midnight.')]
    case EVERY_SUNDAY_AT_MIDNIGHT = '0 0 0 * * 0';

    /**
     * Executes every Saturday and Sunday at midnight.
     */
    #[Label('Every Weekend at Midnight')]
    #[Description('Cron expression for executing a task every Saturday and Sunday at midnight.')]
    case EVERY_WEEKEND_AT_MIDNIGHT = '0 0 0 * * 6,0';

    /**
     * Executes every day at 2:30 PM.
     */
    #[Label('Every Day at 2:30 PM')]
    #[Description('Cron expression for executing a task every day at 2:30 PM.')]
    case EVERY_DAY_AT_2_30PM = '30 14 * *';

    /**
     * Executes every weekday (Monday to Friday) at 2:30 PM.
     */
    #[Label('Every Weekday at 2:30 PM')]
    #[Description('Cron expression for executing a task every weekday (Monday to Friday) at 2:30 PM.')]
    case EVERY_WEEKDAY_AT_2_30PM = '30 14 * * 1-5';

    /**
     * Executes every weekday (Monday to Friday) at 8:45 AM.
     */
    #[Label('Every Weekday at 8:45 AM')]
    #[Description('Cron expression for executing a task every weekday (Monday to Friday) at 8:45 AM.')]
    case EVERY_WEEKDAY_AT_8_45AM = '45 8 * * 1-5';

    /**
     * Executes on the 15th day of every month at 3 PM.
     */
    #[Label('15th Day of Month at 3 PM')]
    #[Description('Cron expression for executing a task on the 15th day of every month at 3 PM.')]
    case EVERY_15TH_DAY_OF_MONTH_AT_3PM = '0 15 15 *';

    /**
     * Executes every hour between 8 AM and 6 PM on weekdays (Monday to Friday).
     */
    #[Label('Every Hour Between 8 AM and 6 PM on Weekdays')]
    #[Description('Cron expression for executing a task every hour between 8 AM and 6 PM on weekdays (Monday to Friday).')]
    case EVERY_HOUR_BETWEEN_8AM_AND_6PM_ON_WEEKDAYS = '0 8-18 * * 1-5';

    /**
     * Executes every 15 minutes.
     */
    #[Label('Every 15 Minutes')]
    #[Description('Cron expression for executing a task every 15 minutes.')]
    case EVERY_15_MINUTES = '*/15 * * *';

    /**
     * Executes every 20 minutes.
     */
    #[Label('Every 20 Minutes')]
    #[Description('Cron expression for executing a task every 20 minutes.')]
    case EVERY_20_MINUTES = '*/20 * * *';

    /**
     * Executes every 45 minutes.
     */
    #[Label('Every 45 Minutes')]
    #[Description('Cron expression for executing a task every 45 minutes.')]
    case EVERY_45_MINUTES = '*/45 * * *';
}
