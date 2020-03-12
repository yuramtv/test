<?php

class Analitics {

    public $trend = Array();

    protected static $_Exchange;

    protected static function Factory($class_name)
    {
        $object = $class_name();

        //$obj = Singlton::getInstance();
        return $object;
    }

    public function __construct() {

        //self::$_Exchange = self::Factory($Exchange);
    }

    public function inc_count() {
        $query = "UPDATE `config` SET `value`=`value`+1 WHERE name='ICOUNT' ";
        DB()->prepare($query)->execute();
    }

    public function get_trend($Exchange) {

        try {

        self::$_Exchange = self::Factory($Exchange);

        $ticker = self::$_Exchange->ticker(self::$_Exchange->curr_pair); ### краткая информация о паре

        //return $ticker;

        if(isset($ticker['error']))
            throw new Exception( $Exchange."-ticker::".$ticker['error'] );

        $last_price = $ticker['last'];

        $result['last_price'] = $last_price;

        ### Объёмы торгов за время SUMM_TIME

        $trades = self::$_Exchange->last_trades(self::$_Exchange->curr_pair);

        //return $trades;

        $volume_buy = 0;
        $volume_sell = 0;

        foreach ($trades as $sKey => $aItem) {

            // мапинг общей суммы (и порога)
            switch ($Exchange) {
                default:
                    $amount = 0;
                    $volume_trashhold = VOLUME_TRASHHOLD_HHLL;
                    $volume_avg_HHLL = VOLUME_AVG_HHLL;
                    $time = 0;
                    break;
                case "Bitfinex":
                    $amount = $aItem['price'] * $aItem['amount'];
                    $volume_trashhold = VOLUME_TRASHHOLD_BIT_HHLL;
                    $volume_avg_HHLL = VOLUME_AVG_BIT_HHLL;
                    $time = $aItem['timestamp'];
                    break;
                case "LiveCoin":
                    $amount = $aItem['price'] * $aItem['quantity'];
                    $volume_trashhold = VOLUME_TRASHHOLD_HHLL;
                    $volume_avg_HHLL = VOLUME_AVG_HHLL;
                    $time = $aItem['time'];
                    break;
                case "EXMO":
                    $amount = $aItem['amount'];
                    $volume_trashhold = VOLUME_TRASHHOLD_HHLL;
                    $volume_avg_HHLL = VOLUME_AVG_HHLL;
                    $time = $aItem['date'];
                    break;
                case "YoBit":

                    $aItem = (array)$aItem;

                    $amount = $aItem['price']*$aItem['amount'];
                    $volume_trashhold = VOLUME_TRASHHOLD_HHLL;
                    $volume_avg_HHLL = VOLUME_AVG_HHLL;
                    $time = $aItem['timestamp'];
                    break;
            }

            if ($time > (UNIX - SUMM_TIME * 60)) {     // если SUMM_TIME == 2, то SUMM_TIME *60 = 2 минуты

                // ask - продажа, bid - покупка

                switch ($aItem['type']) {
                    default:
                        ErrorLog('Analitics:106-неизвестный тип', $aItem['type']);
                        break;
                    case "BUY": //  LiveCoin
                    case "buy": // Bitfinex, EXMO
                    case "bid": // YoBit
                        $volume_buy += $amount;
                        break;
                    case "SELL":  // LiveCoin
                    case "sell":  // Bitfinex, EXMO
                    case "ask":  // YoBit
                        $volume_sell += $amount;
                        break;
                }
            }
        }
            // прогноз по объёмам торгов (выясняем, что больше Sell или Buy)

            $prognoz_volume = "NOT";

            if ($volume_sell - $volume_buy > $volume_trashhold) {

                $prognoz_volume = "DUMP";

            } else if ($volume_buy - $volume_sell > $volume_trashhold) {

                $prognoz_volume = "PUMP";

            }

            $result['volume_sell'] = $volume_sell;
            $result['volume_buy'] = $volume_buy;

            $prognoz['volume'] = $prognoz_volume;

            $result['volume'] = $volume_buy - $volume_sell;

            ### Анализируем открытые ордера

            $info = self::$_Exchange->get_book(self::$_Exchange->curr_pair); ### открытые ордера

            //return $info;

            if( $info == null)
                throw new Exception( "Analitics:145: ".$Exchange." не пришёл ответ get_book" );

            // мапинг возвращаемых массивов
            switch($Exchange){
                default:
                    throw new Exception( "Analitics:152:get_book: неизвестный тип: ". $Exchange);
                    break;
                case "Bitfinex":

                    if(!isset($info['asks']) or !isset($info['bids']))
                        throw new Exception( "Bitfinex get_book error".debug($info) );

                    break;
                case "LiveCoin":

                    if(!isset($info['asks']) or !isset($info['bids']))
                        throw new Exception( "LiveCoin get_book error".debug($info) );

                    break;
                case "YoBit":

                    if(!isset($info['asks']) or !isset($info['bids']))
                        throw new Exception( "YoBit get_book error:".debug($info) );

                    break;
                case "EXMO":

                    $info = $info[self::$_Exchange->curr_pair];

                    if(!isset($info['ask']) or !isset($info['bid']))
                        throw new Exception( "EXMO get_book error".debug($info) );

                    $info['asks'] =  $info['ask'];
                    unset($info['ask']);
                    $info['bids'] =  $info['bid'];
                    unset($info['bid']);

                    break;
            }

            #### продажа

            $count_asks = 0;
            $amount_asks = 0;
            $summ_asks = 0;
            $min_sell_price = 0;

            foreach ($info['asks'] as $key => $value) {

                //debug ($value);

                // мапинг значений
                switch($Exchange){
                    default:
                        throw new Exception( "get_book (asks): неизвестный тип");
                        break;
                    case "Bitfinex":
                        $price = $value['price'];
                        $amount = $value['amount'];
                        $summ = $price*$amount;
                        break;
                    case "LiveCoin":
                    case "YoBit":
                        $price = $value[0];
                        $amount = $value[1];
                        $summ = $price*$amount;
                        break;
                    case "EXMO":
                        $price = $value[0];
                        $amount = $value[1];
                        $summ = $value[2];
                        break;
                }

                if ($min_sell_price == 0 and $amount >= CUTOFF_SMALL_ORDERS) {
                    $min_sell_price = $price;
                }

                if ($price < $last_price * (1 + CUT_PRICE / 100)) {
                    $count_asks += 1;
                    $amount_asks += $amount;
                    $summ_asks += $summ;
                }
            }

            $result['count_asks'] = $count_asks;
            $result['min_sell_price'] = $min_sell_price;
            $result['amount_asks'] = $amount_asks;
            $result['summ_asks'] = $summ_asks;

            //$avg_price_asks = $summ_asks / $amount_asks;

            #### покупка

            $count_bids = 0;
            $amount_bids = 0;
            $summ_bids = 0;
            $max_buy_price = 0;

            //debug ($info['bids']);

            foreach ($info['bids'] as $key => $value) {

                //debug ($value);

                // мапинг значений
                switch($Exchange){
                    default:
                        throw new Exception( "get_book (bids): неизвестный тип");
                        break;
                    case "Bitfinex":
                        $price = $value['price'];
                        $amount = $value['amount'];
                        $summ = $price*$amount;
                        break;
                    case "LiveCoin":
                    case "YoBit":
                        $price = $value[0];
                        $amount = $value[1];
                        $summ = $price*$amount;
                        break;
                    case "EXMO":
                        $price = $value[0];
                        $amount = $value[1];
                        $summ = $value[2];
                        break;
                }

                if ($price > $last_price * (1 - CUT_PRICE / 100)) {

                    if ($max_buy_price == 0 and $amount >= CUTOFF_SMALL_ORDERS) {
                        $max_buy_price = $price;
                    }

                    $count_bids += 1;
                    $amount_bids += $amount;
                    $summ_bids += $summ;

                }
            }

            $result['count_bids'] = $count_bids;
            $result['max_buy_price'] = $max_buy_price;
            $result['amount_bids'] = $amount_bids;
            $result['summ_bids'] = $summ_bids;

            //$avg_price_bids = $summ_bids / $amount_bids;

            // прогноз на разнице суммы ордеров в срезе

            $delta = (($summ_asks - $summ_bids) / ($summ_asks > $summ_bids ? $summ_asks : $summ_bids)) * 100;


            if ($summ_asks > $summ_bids) {

                $prognoz_order_sum = "DOWN";
            } else {
                $prognoz_order_sum = "UP";
            }

            // если разница между продажей и покупкой меньше 5%, то прогноз NOT
            switch (abs($delta) > 5) {
                case true:
                    //$peak = false;
                    break;
                case false:
                    $prognoz_order_sum = "NOT";
                    //$peak = true;
                    break;
            }

            $prognoz['order_sum'] = $prognoz_order_sum;

            //////////
            ### определяем направление движения тренда (анализируем информацию из БД)

            // что больше - AVG_POINT или NUMBER_POINT
            // AVG_POINT (10) - направление движения тренда по среднему значению (avg)
            // POINT_POINT (5)  - направление движения тренда по точкам (point)

            if(POINT_POINT > AVG_POINT) {
                $number_point = POINT_POINT;
            } else {
                $number_point = AVG_POINT;
            }

            $query = "SELECT * FROM depth_".strtolower($Exchange)." ORDER BY id DESC LIMIT 0," . $number_point;

            $history = DB()->query($query)->fetchAll(PDO::FETCH_ASSOC);

            //return ($history);

            $p_count = 0;

            // флаг - нулевые значения в точках
            $point_null = false;

            // средние значения
            $avg_summ = 0;

            $avg_start = 0;     // точка старта
            $avg_finish = 0;    // точка финиша

            // по точкам
            $point_summ = 0;

            $point_start = 0;
            $point_finish = 0;

            $up_count = 0;
            $down_count = 0;

            foreach ($history as $m) {

                $p_count += 1;

                // направление движения по среднему значению
                if($p_count <= AVG_POINT) {

                    if ($p_count == 1) {
                        $avg_start = $m['id'];
                        $result['last_asks_summ'] = $m['asks_summ'];
                        $result['last_bids_summ'] = $m['bids_summ'];
                    }

                    $avg_summ += $m['volume_buy'] - $m['volume_sell'];
                    $avg_finish = $m['id']; // можно вычислить $avg_start + AVG_POINT, но так надёжнее мониторить
                }

                // направление тренда по точкам
                if($p_count <= POINT_POINT) {

                    if ($p_count == 1) {
                        $point_start = $m['id'];
                    }

                    $point_summ += $m['last_price'];

                    // проверка на нулевые значения

                    if ($m['last_price'] == 0) {
                        $point_null = true;
                        //throw new Exception( "Analitics:145:get_trend ".$Exchange." нулевые значения в истории" );
                    }

                    ### направление движения тренда по точкам (анализируем POINT_POINT точек)

                    if ($m['last_price'] > $last_price * (1 + RISE_PERСENT / 100)) {
                        $down_count += 1;
                    }

                    if ($m['last_price'] < $last_price * (1 - RISE_PERСENT / 100)) {
                        $up_count += 1;
                    }

                    $point_finish = $m['id']; // можно вычислить $point_start + POINT_POINT, но так надёжнее мониторить
                }
            }

            // прогноз по среднему значению (суммирование по AVG_POINT точкам)

            $result['avg_start'] = $avg_start;
            $result['avg_finish'] = $avg_finish;
            $result['avg_summ'] = $avg_summ;

            $prognoz_avg = "NOT";

            if ($avg_summ > $volume_avg_HHLL) {
                $prognoz_avg = "PUMP";
            } else if (abs($avg_summ) > $volume_avg_HHLL) {
                $prognoz_avg = "DUMP";
            }

            $prognoz['volume_avg'] = $prognoz_avg;

            // прогноз по точкам
            // сравниваем текущую цену со средним значением в POINT_POINT и порогами

            $result['point_start'] = $point_start;
            $result['point_finish'] = $point_finish;

            $point_avg = $point_summ / POINT_POINT;
            $delta = $last_price - $point_avg;

            $dir = "NOT";

            if ($point_null or $p_count == 0) {
            } else if (abs($delta) > $point_avg * (CUTOFF_HHLL / 100) && $delta > 0) {
                $dir = "PUMP";
            } else if (abs($delta) > $point_avg * (CUTOFF_HL / 100) && $delta > 0) {
                $dir = "UP";
            } else if (abs($delta) > $point_avg * (CUTOFF_HHLL / 100) && $delta < 0) {
                $dir = "DUMP";
            } else if (abs($delta) > $point_avg * (CUTOFF_HL / 100) && $delta < 0) {
                $dir = "DOWN";
            }

            $prognoz['point_dir'] = $dir;
            $result['point_avg'] = $point_avg;
            $result['delta'] = $delta;

            // анализ по точкам (подсчитываем $up_count и $down_count в POINT_POINT точках, учитываем пороги)

            if ($up_count > $down_count + round(POINT_POINT * 0.9)) {
                $dir = "PUMP";
            } else if ($up_count >= $down_count + round(POINT_POINT * 0.5)) {
                $dir = "UP";
            } else if ($down_count > $up_count + round(POINT_POINT * 0.9)) {
                $dir = "DUMP";
            } else if ($down_count >= $up_count + round(POINT_POINT * 0.5)) {
                $dir = "DOWN";
            } else {
                $dir = "NOT";
            }

            $prognoz['point'] = $dir;
            $result['result'] = 1;

            ### изменение стенок

            $prognoz['wall'] = false;

            $result['wall_ask_delta'] = $result['last_asks_summ'] - $summ_asks;
            $result['wall_bids_delta'] = $result['last_bids_summ'] - $summ_bids;

            // продажа
            if ($result['wall_ask_delta'] > WALL_FALL) {
                $prognoz['wall'] = 'SELL';
            }
            // покупка
            if ($result['wall_bids_delta'] > WALL_FALL) {
                $prognoz['wall'] = 'BUY';
            }

            // итоговое решение по направлению тренда

            if($prognoz['volume'] == "PUMP" and $prognoz['wall'] == 'BUY' ) {
                $prognoz['final'] = "PUMP";
            }

            // продажа
            else if($prognoz['volume'] == "DUMP" and $prognoz['wall'] == 'SELL' ) {
                $prognoz['final'] = "DUMP";
            } else {

                $prognoz['final'] = "NOT";
            }

            $result['prognoz'] = $prognoz;

            $this->trend[$Exchange] = $result;

        }  catch( Exception $e ) {

            $result['result'] = 0;
            $result['error'] = $e->getMessage();

            $result['volume_sell'] = 0;
            $result['volume_buy'] = 0;

           // $result['prognoz_volume'] = 'NOT';
           // $result['prognoz_vol_avg'] = 'NOT';

            $result['last_price'] = 0;

            $prognoz['final'] = "NOT";

            ErrorLog( 'Analitics:GetTrend:final:521 ('.$Exchange.")", $e->getMessage() );

        }

        return $result;
    }

    public function reset_history() {

        try {

        if(!DB()->prepare("TRUNCATE TABLE `depth_exmo`")->execute()) {
            //echo "error: TRUNCATE TABLE depth_exmo<br/>";
            throw new Exception('depth_exmo');
        }

        if(!DB()->prepare("TRUNCATE TABLE `depth_bitfinex`")->execute()) {
            //echo "error: TRUNCATE TABLE depth_bitfinex<br/>";
            throw new Exception('depth_bitfinex');
        }

        if(!DB()->prepare("TRUNCATE TABLE `depth_yobit`")->execute()) {
            //echo "error: TRUNCATE TABLE depth_yobit<br/>";
            throw new Exception('depth_yobit');
        }

        if(!DB()->prepare("TRUNCATE TABLE `depth_livecoin`")->execute()) {
            //echo "error: TRUNCATE TABLE depth_livecoin<br/>";
            throw new Exception('depth_livecoin');
        }

            //$query = "UPDATE `config` SET `value`= '0' WHERE name='ICOUNT' ";
            if(! DB()->prepare("UPDATE `config` SET `value`= '0' WHERE name='ICOUNT' ")->execute()) {
                throw new Exception('сброс ICOUNT');
            }

    } catch( Exception $e ) {

            ErrorLog( 'Analitics:reset_history:552', $e->getMessage() );
        }
    }
}
?>
