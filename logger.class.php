<?php

/**
 * Debug logging during a client request.
 * So you can measure any action find bottlenecks in your code.
 * 
 * Source: https://github.com/axelhahn/ahlogger
 * 
 * USAGE:
 * (1) Trigger a message with add() to add a marker
 * (2) The render() method lists all items in a table with time since start
 *     and the delta to the last message. 
 * 
 * @author www.axel-hahn.de
 */
class logger {

    protected $aMessages = [];
    protected $bShowDebug = false;
    protected $_iMemStart = false;

    /**
     * constuctor
     * @param  string $sInitMessage  init message
     * @return boolean
     */
    public function __construct($sInitMessage = "Logger was initialized.") {
        $this->_iMemStart=memory_get_usage();
        $this->enableDebug(true);
        $this->add($sInitMessage);
        $this->sCssPrefix='debug-'.md5(microtime(true));
        return true;
    }

    /**
     * add a logging message
     * @param type $sMessage
     * @param type $sLevel
     * @return boolean
     */
    public function add($sMessage, $sLevel = "info") {
        if (!$this->bShowDebug){
            return false;
        }        
        $this->aMessages[] = array(
            'time' => microtime(true),
            'message' => $sMessage,
            'level' => preg_replace('/[^a-z0-9\-\_]/', '', $sLevel),
            'memory' => memory_get_usage()
        );

        return true;
    }

    /**
     * enable / disable debugging
     * @param type $bEnable
     * @return type
     */
    public function enableDebug($bEnable=true){
        return $this->bShowDebug=!!$bEnable;
    }

    /**
     * enable client debugging by a given array of allowed ip addresses
     * @param array $aIpArray list of ip addresses in a flat array
     * @return boolean
     */
    public function enableDebugByIp($aIpArray){
        $this->enableDebug(false);
        if (!$_SERVER || !is_array($_SERVER) || !array_key_exists("REMOTE_ADDR", $_SERVER)){
            return false;
        }
        if (array_search($_SERVER['REMOTE_ADDR'], $aIpArray)!==false){
            $this->enableDebug(true);
        }
    }

    protected function _prepareRendering(){
        $iMem=memory_get_usage();
        $this->add('<hr>');
        $this->add('Memory on start: ' . number_format($this->_iMemStart, 0, '.', ',') . " bytes");
        $this->add('Memory on end: '   . number_format($iMem, 0, '.', ',') . " bytes");
        $this->add('Memory peak: '  . number_format(memory_get_peak_usage(), 0, '.', ',') . " bytes");

        $aReturn=[
            'totaltime' => false,
            'level' => false,
            'warnings' => '',
            'errors' => '',
            'maxrowid' => false,
            'maxtime' => false,
            'result' => []
        ];
        $sStarttime = $this->aMessages[0]["time"];
        $iLasttime = $sStarttime;
        $iCounter = 0;
        $sMaxRowId = false;
        $iMaxtime = -1;
        $iMaxmem = -1;
        $bHasWarning = false;
        $bHasError = false;

        foreach ($this->aMessages as $aLogentry) {
            $iCounter++;

            if($aLogentry["level"]=="warning"){
                $bHasWarning=true;
            }
            if($aLogentry["level"]=="error"){
                $bHasError=true;
            }

            $sTrId = $this->sCssPrefix.'debugTableRow' . $iCounter;
            $iDelta = $aLogentry["time"] - $iLasttime;
            if ($iDelta > $iMaxtime) {
                $iMaxtime = $iDelta;
                $sMaxRowId = $sTrId;
            }
            $iMaxmem=max($aLogentry["memory"], $iMaxmem);


            if (($iDelta > 1) || $aLogentry["level"] == "warning") {
                $aReturn['warnings'].='<a href="#' . $sTrId . '" title="' . sprintf("%01.4f", $iDelta) . ' s">' . $iCounter . '</a>&nbsp;';
            }
            if ($aLogentry["level"] == "error") {
                $aReturn['errors'].='<a href="#' . $sTrId . '" title="' . sprintf("%01.4f", $iDelta) . ' s">' . $iCounter . '</a>&nbsp;';
            }
            $aReturn['entries'][]=[
                'time'=>$aLogentry["time"],
                'level'=>$aLogentry["level"],
                'message'=>$aLogentry["message"],
                'memory'=>sprintf("%01.2f", $aLogentry["memory"]/1024/1024), // MB

                'trid'=>$sTrId,
                'trclass'=>$aLogentry["level"],
                'counter'=>$iCounter,
                'timer'=>sprintf("%01.3f", $aLogentry["time"] - $sStarttime),
                'delta'=>sprintf("%01.0f", $iDelta*1000),
            ];
            $iLasttime = $aLogentry["time"];
        }
        $aReturn['level']=($bHasWarning
            ? ($bHasError ? 'error' : 'warning')
            : ''
        );
        $aReturn['maxrowid']=$sMaxRowId;
        $aReturn['maxtime']=sprintf("%01.3f", $iMaxtime);
        $aReturn['maxmem']=sprintf("%01.2f", $iMaxmem/1024/1024);
        $aReturn['totaltime']=sprintf("%01.3f", $aLogentry['time']-$aReturn['entries'][0]['time']);
        return $aReturn;
    }

    /**
     * get html code for a progressbar with divs
     * @param  {int|float}  $iVal  value between 0..max value
     * @param  {int|float}  $iMax  max value
     * @return {string}
     */
    protected function _getBar($iVal, $iMax){
        $iWidth=$iVal/$iMax*100;
        return '<div class="bar"><div class="progress" style="width: '.$iWidth.'%;">&nbsp;</div></div>';
    }

    /**
     * render output of all logging messages
     */
    public function render() {
        if (!$this->bShowDebug){
            return false;
        }
        $aData=$this->_prepareRendering();

        /*
        Array
        (
            [totaltime] => 0.006
            [errors] =>  
            [warnings] => 3 
            [maxrowid] => debugTableRow3
            [maxtime] => 0.005
            [result] => Array
                (
                )

            [entries] => Array
                mit Elementen
                Array
                    (
                        [time] => 1663959608.2566
                        [level] => info
                        [message] => Logger was initialized.
                        [memory] => 538056
                        [trid] => debugTableRow1
                        [trclass] => info
                        [trstyle] => 
                        [counter] => 1
                        [timer] => 0.000
                        [delta] => 0.000
                    )
        */

        $sOut='';
        // echo '<pre>'; print_r($aData); die();
        foreach ($aData['entries'] as $aLogentry){
            $sOut.='<tr class="'.$this->sCssPrefix.'-level-' . $aLogentry["level"] . ''.($aLogentry["trid"]==$aData["maxrowid"] ? ' '.$this->sCssPrefix.'-maxrow' : '').'" '
                .'id="' . $aLogentry["trid"] . '">' .
                    '<td>' . $aLogentry["counter"] . '</td>' .
                    '<td>' . $aLogentry["level"] . '</td>' .
                    '<td>' . $aLogentry["timer"] . '</td>' .
                    '<td>' . $this->_getBar($aLogentry["delta"], $aData["maxtime"]*1000). $aLogentry["delta"] .' ms</td>' .
                    '<td>' . $this->_getBar($aLogentry["memory"], $aData["maxmem"]) . $aLogentry["memory"] .' MB'. '</td>' .
                    '<td>' . $aLogentry["message"] . '</td>' .
                    '</tr>';
        }
        if ($sOut){
            $sOut = '
            <style>
                .'.$this->sCssPrefix.'-info          {position: fixed; top: 6em; right: 1em; background: rgba(160,200,255, 0.3); border: 1px solid; z-index: 99999;}
                .'.$this->sCssPrefix.'-info .head    {background: rgba(0,0,0,0.4); color: #fff;padding: 0em 0.5em; }
                .'.$this->sCssPrefix.'-info .content {padding: 0.5em; }
                .'.$this->sCssPrefix.'-info .content .total {font-size: 140%; color: rgba(0,0,0,0.5); margin: 0.3em 0; display: inline-block;}

                .'.$this->sCssPrefix.'-messages {margin: 5em 2em 2em;}
                .'.$this->sCssPrefix.'-messages .bar      {background: rgba(0,0,0,0.03); height: 1.4em; position: absolute; width: 6em; border-right: 1px solid rgba(0,0,0,0.2);}
                .'.$this->sCssPrefix.'-messages .progress {background: rgba(100,140,180,0.2); height: 1.4em; padding: 0;}
                .'.$this->sCssPrefix.'-messages table{background: #fff; color: #222;table-layout:fixed; }
                .'.$this->sCssPrefix.'-messages table th{background: none;}
                .'.$this->sCssPrefix.'-messages table th.barcol{min-width: 7em; position: relative;}
                .'.$this->sCssPrefix.'-messages table td{padding: 3px; vertical-align: top;}
                .'.$this->sCssPrefix.'-messages table th:hover{background:#aaa !important;}

                .'.$this->sCssPrefix.'-level-info{background: #e0e8f8; color:#124}
                .'.$this->sCssPrefix.'-level-warning{background: #fcf8e3; color: #980;}
                .'.$this->sCssPrefix.'-level-error{background: #fce0e0; color: #944;}
                .'.$this->sCssPrefix.'-maxrow{color:#f33; font-weight: bold;}
            </style>
            <div class="'.$this->sCssPrefix.' '.$this->sCssPrefix.'-info '.$this->sCssPrefix.'-level-'.$aData['level'].'">
                <div class="head">ahLogger</div>
                <div class="content">
                    <span class="total">' . $aData['totaltime'] . '&nbsp;s</span><br>
                    <a href="#'.$this->sCssPrefix.'-messages">Debug infos</a> | <a href="#">top</a><br>
                    <span>longest&nbsp;action:&nbsp;<a href="#' . $aData['maxrowid'] . '">' . ($aData['maxtime']*1000) . '&nbsp;ms</a></span>
                    ' . ($aData['errors'] ? '<br><span>Errors: '.$aData['errors'] . '</span>' : '').'
                    ' . ($aData['warnings'] ? '<br><span>Warnings: '.$aData['warnings'] . '</span>' : '').'
                </div>
            </div>

            <div id="'.$this->sCssPrefix.'-messages" class="'.$this->sCssPrefix.' '.$this->sCssPrefix.'-messages">
            DEBUG :: LOG MESSAGES<br>'
            . ($aData['errors']   ? '<span>Errors: '.$aData['errors'] . '</span><br>' : '')
            . ($aData['warnings'] ? '<span>Warnings: '.$aData['warnings'] . '</span><br>' : '')
            .'<br>
            <table >
            <thead>
            <tr>
                <th>#</th>
                <th>level</th>
                <th>time [s]</th>
                <th class="barcol">delta</th>
                <th class="barcol">memory</th>
                <th>message</th>
            </tr></thead><tbody>
            ' . $sOut . '</tbody></table>'
            ;
		}
        return $sOut;
    }
   /**
     * render output of all logging messages for cli output
     * @return string
     */
    public function renderCli(){
        if (!$this->bShowDebug){
            return false;
        }
        $aData=$this->_prepareRendering();

        $sOut='';
        foreach ($aData['entries'] as $aLogentry){
            $sOut.=$aLogentry["timer"].' | '
                    .$aLogentry["delta"].' ms | '
                    .$aLogentry["level"].' | '
                    .(sprintf("%01.3f", $aLogentry["memory"]/1024/1024)).' MB | '
                   .$aLogentry["message"].' '
                   . "\n"
                   ;
        }
        $sOut.="\nTotal time: ".$aData['totaltime'] . "\n";
        return $sOut;
    }
}
