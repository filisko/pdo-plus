<?php
namespace Filisko\PDOplus\Tracy;

class BarPanel implements \Tracy\IBarPanel
{
    /**
     * Base64 icon for Tracy panel.
     * @var string
     * @see http://www.flaticon.com/free-icon/database_51319
     * @author Freepik.com
     * @license http://file000.flaticon.com/downloads/license/license.pdf
     */
    public $icon = 'data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDk1LjEwMyA5NS4xMDMiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk1LjEwMyA5NS4xMDM7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0iTGF5ZXJfMV8xNF8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxnPgoJCQkJCTxwYXRoIGQ9Ik00Ny41NjEsMEMyNS45MjgsMCw4LjM5LDYuMzkzLDguMzksMTQuMjgzdjExLjcyYzAsNy44OTEsMTcuNTM4LDE0LjI4MiwzOS4xNzEsMTQuMjgyICAgICAgIGMyMS42MzIsMCwzOS4xNy02LjM5MiwzOS4xNy0xNC4yODJ2LTExLjcyQzg2LjczMSw2LjM5Myw2OS4xOTMsMCw0Ny41NjEsMHoiIGZpbGw9IiMyYjJiMmIiLz4KCQkJCTwvZz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxnPgoJCQkJCTxwYXRoIGQ9Ik00Ny41NjEsNDcuMTE1Yy0yMC42NTQsMC0zNy42ODItNS44MzItMzkuMTcxLTEzLjIyN2MtMC4wNzEsMC4zNTMsMCwxOS4zNTUsMCwxOS4zNTUgICAgICAgYzAsNy44OTIsMTcuNTM4LDE0LjI4MywzOS4xNzEsMTQuMjgzYzIxLjYzMiwwLDM5LjE3LTYuMzkzLDM5LjE3LTE0LjI4M2MwLDAsMC4wNDQtMTkuMDAzLTAuMDI2LTE5LjM1NSAgICAgICBDODUuMjE0LDQxLjI4NCw2OC4yMTQsNDcuMTE1LDQ3LjU2MSw0Ny4xMTV6IiBmaWxsPSIjMmIyYjJiIi8+CgkJCQk8L2c+CgkJCTwvZz4KCQkJPHBhdGggZD0iTTg2LjY5NCw2MS40NjRjLTEuNDg4LDcuMzkxLTE4LjQ3OSwxMy4yMjYtMzkuMTMzLDEzLjIyNlM5Ljg3NSw2OC44NTQsOC4zODYsNjEuNDY0TDguMzksODAuODIgICAgIGMwLDcuODkxLDE3LjUzOCwxNC4yODIsMzkuMTcxLDE0LjI4MmMyMS42MzIsMCwzOS4xNy02LjM5MywzOS4xNy0xNC4yODJMODYuNjk0LDYxLjQ2NHoiIGZpbGw9IiMyYjJiMmIiLz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==';

    /**
     * Title
     * @var string
     */
    public $title = 'PDO logger';

    /**
     * Title HTML attributes
     * @var string
     */
    public $title_attributes = 'style="font-size:1.6em"';

    /**
     * Time table cell HTML attributes
     * @var string
     */
    public $time_attributes = 'style="font-weight:bold;color:#333;font-family:Courier New;font-size:1.1em"';

    /**
     * Query table cell HTML attributes
     * @var string
     */
    public $query_attributes = '';

    /**
     * PDO logged queries
     * @var array
     */
    protected $queries;

    public function __construct(\Filisko\PDOplus\PDO $pdo)
    {
        $this->queries = $pdo->getLog();
    }

    /**
	 * Get total queries execution time
	 * @return string
	 */
    protected function getTotalTime()
    {
        $time = round(array_sum(array_column($this->queries, 'time')), 4);
        return $time;
    }

    /**
	 * Renders HTML code for custom tab.
	 * @return string
	 */
    public function getTab()
    {
        $html = '<img src="'.$this->icon.'" alt="PDO queries logger" /> ';
        $queries = count($this->queries);
        if ($queries == 0) {
            $html .= 'no queries!';
            return $html;
        } elseif ($queries == 1) {
            $html .= '1 query';
        } else {
            $html .= $queries . ' queries';
        }
        $html .= ' / '.$this->getTotalTime().'ms';
        return $html;
    }

    /**
	 * Renders HTML code for custom panel.
	 * @return string
	 */
    public function getPanel()
    {
        if (class_exists('\SqlFormatter')) {
            \SqlFormatter::$pre_attributes = 'style="color: black;"';
        }
        $queries = $this->queries;
        $html = '<h1 '.$this->title_attributes.'>'.$this->title.'</h1>';
        $html .= '<div class="tracy-inner tracy-InfoPanel">';
        if (count($queries) > 0) {
            $html .= '<table style="width:400px;">';
            $html .= '<tr>';
            $html .= '<th>Time(ms)</td>';
            $html .= '<th>Statement</td>';
            $html .= '</tr>';
            foreach ($queries as $query) {
                $html .= '<tr>';
                $html .= '<td><span '.$this->time_attributes.'>'.round($query['time'], 4).'</span></td>';
                if (class_exists('\SqlFormatter')) {
                    $html .= '<td>'.\SqlFormatter::highlight($query['statement']).'</td>';
                } else {
                    $html .= '<td '.$this->query_attributes.'>'.$query['statement'].'</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p style="font-size:1.2em;font-weigt:bold;padding:10px">No queries were executed!</p>';
        }
        $html .= '</div>';

        return $html;
    }
}
