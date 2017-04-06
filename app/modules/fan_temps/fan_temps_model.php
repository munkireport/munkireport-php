<?php

class Fan_temps_model extends Model {
    
	function __construct($serial='')
	{
		parent::__construct('id', 'fan_temps'); //primary key, tablename
		$this->rs['id'] = 0;
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['fan_0'] = -9876540;
		$this->rs['fan_1'] = -9876540;
		$this->rs['fan_2'] = -9876540;
		$this->rs['fan_3'] = -9876540;
		$this->rs['fan_4'] = -9876540;
		$this->rs['fan_5'] = -9876540;
		$this->rs['fan_6'] = -9876540;
		$this->rs['fan_7'] = -9876540;
		$this->rs['fan_8'] = -9876540;
		$this->rs['fanmin0'] = -9876540;
		$this->rs['fanmin1'] = -9876540;
		$this->rs['fanmin2'] = -9876540;
		$this->rs['fanmin3'] = -9876540;
		$this->rs['fanmin4'] = -9876540;
		$this->rs['fanmin5'] = -9876540;
		$this->rs['fanmin6'] = -9876540;
		$this->rs['fanmin7'] = -9876540;
		$this->rs['fanmin8'] = -9876540;
		$this->rs['fanmax0'] = -9876540;
		$this->rs['fanmax1'] = -9876540;
		$this->rs['fanmax2'] = -9876540;
		$this->rs['fanmax3'] = -9876540;
		$this->rs['fanmax4'] = -9876540;
		$this->rs['fanmax5'] = -9876540;
		$this->rs['fanmax6'] = -9876540;
		$this->rs['fanmax7'] = -9876540;
		$this->rs['fanmax8'] = -9876540;
		$this->rs['fanlabel0'] = "";
		$this->rs['fanlabel1'] = "";
		$this->rs['fanlabel2'] = "";
		$this->rs['fanlabel3'] = "";
		$this->rs['fanlabel4'] = "";
		$this->rs['fanlabel5'] = "";
		$this->rs['fanlabel6'] = "";
		$this->rs['fanlabel7'] = "";
		$this->rs['fanlabel8'] = "";
		$this->rs['discin'] = "";
		// Keep these mostly alphabetized to properly order client tab
		$this->rs['ta0p'] = -9876540; $this->rt['ta0p'] = 'FLOAT';
		$this->rs['ta1p'] = -9876540; $this->rt['ta1p'] = 'FLOAT';
		$this->rs['ta2p'] = -9876540; $this->rt['ta2p'] = 'FLOAT';
		$this->rs['ta0p2'] = -9876540; $this->rt['ta0p2'] = 'FLOAT';
		$this->rs['ta1p2'] = -9876540; $this->rt['ta1p2'] = 'FLOAT';
		$this->rs['ta0g'] = -9876540; $this->rt['ta0g'] = 'FLOAT';
		$this->rs['ta2g'] = -9876540; $this->rt['ta2g'] = 'FLOAT';
		$this->rs['ta0s'] = -9876540; $this->rt['ta0s'] = 'FLOAT';
		$this->rs['ta1s'] = -9876540; $this->rt['ta1s'] = 'FLOAT';
		$this->rs['ta2s'] = -9876540; $this->rt['ta2s'] = 'FLOAT';
		$this->rs['ta3s'] = -9876540; $this->rt['ta3s'] = 'FLOAT';
		$this->rs['ta4s'] = -9876540; $this->rt['ta4s'] = 'FLOAT';
		$this->rs['ta5s'] = -9876540; $this->rt['ta5s'] = 'FLOAT';
		$this->rs['talc'] = -9876540; $this->rt['talc'] = 'FLOAT';
		$this->rs['tarc'] = -9876540; $this->rt['tarc'] = 'FLOAT';
		$this->rs['tb0p'] = -9876540; $this->rt['tb0p'] = 'FLOAT';
		$this->rs['tb0t'] = -9876540; $this->rt['tb0t'] = 'FLOAT';
		$this->rs['tb1t'] = -9876540; $this->rt['tb1t'] = 'FLOAT';
		$this->rs['tb2t'] = -9876540; $this->rt['tb2t'] = 'FLOAT';
		$this->rs['tb3t'] = -9876540; $this->rt['tb3t'] = 'FLOAT';
		$this->rs['tbxt'] = -9876540; $this->rt['tbxt'] = 'FLOAT';
        $this->rs['tc0c'] = -9876540; $this->rt['tc0c'] = 'FLOAT';
		$this->rs['tc0d'] = -9876540; $this->rt['tc0d'] = 'FLOAT';
		$this->rs['tc0d2'] = -9876540; $this->rt['tc0d2'] = 'FLOAT';
		$this->rs['tc0e'] = -9876540; $this->rt['tc0e'] = 'FLOAT';
		$this->rs['tc0f'] = -9876540; $this->rt['tc0f'] = 'FLOAT';
		$this->rs['tc0g'] = -9876540; $this->rt['tc0g'] = 'FLOAT';
		$this->rs['tc0h'] = -9876540; $this->rt['tc0h'] = 'FLOAT';
		$this->rs['tc0j'] = -9876540; $this->rt['tc0j'] = 'FLOAT';
		$this->rs['tc0p'] = -9876540; $this->rt['tc0p'] = 'FLOAT';
		$this->rs['tc0p2'] = -9876540; $this->rt['tc0p2'] = 'FLOAT';
		$this->rs['tc1c'] = -9876540; $this->rt['tc1c'] = 'FLOAT';
		$this->rs['tc1d'] = -9876540; $this->rt['tc1d'] = 'FLOAT';
		$this->rs['tc1e'] = -9876540; $this->rt['tc1e'] = 'FLOAT';
		$this->rs['tc1f'] = -9876540; $this->rt['tc1f'] = 'FLOAT';
		$this->rs['tc1h'] = -9876540; $this->rt['tc1h'] = 'FLOAT';
		$this->rs['tc1p'] = -9876540; $this->rt['tc1p'] = 'FLOAT';
		$this->rs['tc2c'] = -9876540; $this->rt['tc2c'] = 'FLOAT';
		$this->rs['tc2p'] = -9876540; $this->rt['tc2p'] = 'FLOAT';
		$this->rs['tc3c'] = -9876540; $this->rt['tc3c'] = 'FLOAT';
		$this->rs['tc3p'] = -9876540; $this->rt['tc3p'] = 'FLOAT';
		$this->rs['tc4c'] = -9876540; $this->rt['tc4c'] = 'FLOAT';
		$this->rs['tc5c'] = -9876540; $this->rt['tc5c'] = 'FLOAT';
		$this->rs['tc6c'] = -9876540; $this->rt['tc6c'] = 'FLOAT';
		$this->rs['tc7c'] = -9876540; $this->rt['tc7c'] = 'FLOAT';
		$this->rs['tc8c'] = -9876540; $this->rt['tc8c'] = 'FLOAT';
		$this->rs['tc0c2'] = -9876540; $this->rt['tc0c2'] = 'FLOAT';
		$this->rs['tc1c2'] = -9876540; $this->rt['tc1c2'] = 'FLOAT';
		$this->rs['tc2c2'] = -9876540; $this->rt['tc2c2'] = 'FLOAT';
		$this->rs['tc3c2'] = -9876540; $this->rt['tc3c2'] = 'FLOAT';
		$this->rs['tc2d'] = -9876540; $this->rt['tc2d'] = 'FLOAT';
		$this->rs['tcac'] = -9876540; $this->rt['tcac'] = 'FLOAT';
		$this->rs['tcad'] = -9876540; $this->rt['tcad'] = 'FLOAT';
		$this->rs['tcag'] = -9876540; $this->rt['tcag'] = 'FLOAT';
		$this->rs['tcah'] = -9876540; $this->rt['tcah'] = 'FLOAT';
		$this->rs['tcas'] = -9876540; $this->rt['tcas'] = 'FLOAT';
		$this->rs['tcbc'] = -9876540; $this->rt['tcbc'] = 'FLOAT';
		$this->rs['tcbd'] = -9876540; $this->rt['tcbd'] = 'FLOAT';
		$this->rs['tcbg'] = -9876540; $this->rt['tcbg'] = 'FLOAT';
		$this->rs['tcbh'] = -9876540; $this->rt['tcbh'] = 'FLOAT';
		$this->rs['tcbs'] = -9876540; $this->rt['tcbs'] = 'FLOAT';
		$this->rs['tcfp'] = -9876540; $this->rt['tcfp'] = 'FLOAT';
		$this->rs['tcgc'] = -9876540; $this->rt['tcgc'] = 'FLOAT';
		$this->rs['tcgc2'] = -9876540; $this->rt['tcgc2'] = 'FLOAT';
		$this->rs['tchp'] = -9876540; $this->rt['tchp'] = 'FLOAT';
		$this->rs['tcpg'] = -9876540; $this->rt['tcpg'] = 'FLOAT';
		$this->rs['tcsa'] = -9876540; $this->rt['tcsa'] = 'FLOAT';
		$this->rs['tcsc'] = -9876540; $this->rt['tcsc'] = 'FLOAT';
		$this->rs['tcsc2'] = -9876540; $this->rt['tcsc2'] = 'FLOAT';
		$this->rs['tctd'] = -9876540; $this->rt['tctd'] = 'FLOAT';
		$this->rs['tcxc'] = -9876540; $this->rt['tcxc'] = 'FLOAT';
		$this->rs['tcxc2'] = -9876540; $this->rt['tcxc2'] = 'FLOAT';
		$this->rs['tcsr'] = -9876540; $this->rt['tcsr'] = 'FLOAT';
		$this->rs['te0t'] = -9876540; $this->rt['te0t'] = 'FLOAT';
        $this->rs['te1f'] = -9876540; $this->rt['te1f'] = 'FLOAT';
		$this->rs['te1p'] = -9876540; $this->rt['te1p'] = 'FLOAT';
		$this->rs['te1s'] = -9876540; $this->rt['te1s'] = 'FLOAT';
		$this->rs['te2f'] = -9876540; $this->rt['te2f'] = 'FLOAT';
		$this->rs['te2s'] = -9876540; $this->rt['te2s'] = 'FLOAT';
		$this->rs['te3f'] = -9876540; $this->rt['te3f'] = 'FLOAT';
		$this->rs['te3s'] = -9876540; $this->rt['te3s'] = 'FLOAT';
		$this->rs['te4f'] = -9876540; $this->rt['te4f'] = 'FLOAT';
		$this->rs['te4s'] = -9876540; $this->rt['te4s'] = 'FLOAT';
		$this->rs['te5f'] = -9876540; $this->rt['te5f'] = 'FLOAT';
		$this->rs['te5s'] = -9876540; $this->rt['te5s'] = 'FLOAT';
		$this->rs['tegg'] = -9876540; $this->rt['tegg'] = 'FLOAT';
		$this->rs['tegp'] = -9876540; $this->rt['tegp'] = 'FLOAT';
		$this->rs['terg'] = -9876540; $this->rt['terg'] = 'FLOAT';
		$this->rs['tetp'] = -9876540; $this->rt['tetp'] = 'FLOAT';
		$this->rs['tg0d'] = -9876540; $this->rt['tg0d'] = 'FLOAT';
		$this->rs['tg0g'] = -9876540; $this->rt['tg0g'] = 'FLOAT';
		$this->rs['tg0h'] = -9876540; $this->rt['tg0h'] = 'FLOAT';
		$this->rs['tg0p'] = -9876540; $this->rt['tg0p'] = 'FLOAT';
        $this->rs['tg0p2'] = -9876540; $this->rt['tg0p2'] = 'FLOAT';
		$this->rs['tg0r'] = -9876540; $this->rt['tg0r'] = 'FLOAT';
		$this->rs['tg0t'] = -9876540; $this->rt['tg0t'] = 'FLOAT';
		$this->rs['tg1d'] = -9876540; $this->rt['tg1d'] = 'FLOAT';
		$this->rs['tg1f'] = -9876540; $this->rt['tg1f'] = 'FLOAT';
		$this->rs['tg1h'] = -9876540; $this->rt['tg1h'] = 'FLOAT';
		$this->rs['tg1d2'] = -9876540; $this->rt['tg1d2'] = 'FLOAT';
		$this->rs['tg1p'] = -9876540; $this->rt['tg1p'] = 'FLOAT';
		$this->rs['tg1r'] = -9876540; $this->rt['tg1r'] = 'FLOAT';
		$this->rs['tgtc'] = -9876540; $this->rt['tgtc'] = 'FLOAT';
		$this->rs['tgtd'] = -9876540; $this->rt['tgtd'] = 'FLOAT';
		$this->rs['tgvp'] = -9876540; $this->rt['tgvp'] = 'FLOAT';
		$this->rs['th0a'] = -9876540; $this->rt['th0a'] = 'FLOAT';
		$this->rs['th0a2'] = -9876540; $this->rt['th0a2'] = 'FLOAT';
		$this->rs['th0b'] = -9876540; $this->rt['th0b'] = 'FLOAT';
		$this->rs['th0b2'] = -9876540; $this->rt['th0b2'] = 'FLOAT';
		$this->rs['th0c'] = -9876540; $this->rt['th0c'] = 'FLOAT';
		$this->rs['th0c2'] = -9876540; $this->rt['th0c2'] = 'FLOAT';
		$this->rs['th0f'] = -9876540; $this->rt['th0f'] = 'FLOAT';
		$this->rs['th0h'] = -9876540; $this->rt['th0h'] = 'FLOAT';
		$this->rs['th0o'] = -9876540; $this->rt['th0o'] = 'FLOAT';
		$this->rs['th0p'] = -9876540; $this->rt['th0p'] = 'FLOAT';
		$this->rs['th0r'] = -9876540; $this->rt['th0r'] = 'FLOAT';
		$this->rs['th0v'] = -9876540; $this->rt['th0v'] = 'FLOAT';
		$this->rs['th0x'] = -9876540; $this->rt['th0x'] = 'FLOAT';
		$this->rs['th0x2'] = -9876540; $this->rt['th0x2'] = 'FLOAT';
		$this->rs['th1a'] = -9876540; $this->rt['th1a'] = 'FLOAT';
		$this->rs['th1a2'] = -9876540; $this->rt['th1a2'] = 'FLOAT';
		$this->rs['th1b'] = -9876540; $this->rt['th1b'] = 'FLOAT';
		$this->rs['th1b2'] = -9876540; $this->rt['th1b2'] = 'FLOAT';
		$this->rs['th1c'] = -9876540; $this->rt['th1c'] = 'FLOAT';
		$this->rs['th1c2'] = -9876540; $this->rt['th1c2'] = 'FLOAT';
		$this->rs['th1f'] = -9876540; $this->rt['th1f'] = 'FLOAT';
		$this->rs['th1g'] = -9876540; $this->rt['th1g'] = 'FLOAT';
		$this->rs['th1h'] = -9876540; $this->rt['th1h'] = 'FLOAT';
		$this->rs['th1o'] = -9876540; $this->rt['th1o'] = 'FLOAT';
		$this->rs['th1p'] = -9876540; $this->rt['th1p'] = 'FLOAT';
		$this->rs['th1r'] = -9876540; $this->rt['th1r'] = 'FLOAT';
		$this->rs['th1v'] = -9876540; $this->rt['th1v'] = 'FLOAT';
		$this->rs['th1x'] = -9876540; $this->rt['th1x'] = 'FLOAT';
		$this->rs['th2f'] = -9876540; $this->rt['th2f'] = 'FLOAT';
		$this->rs['th2g'] = -9876540; $this->rt['th2g'] = 'FLOAT';
		$this->rs['th2h'] = -9876540; $this->rt['th2h'] = 'FLOAT';
		$this->rs['th2p'] = -9876540; $this->rt['th2p'] = 'FLOAT';
		$this->rs['th2v'] = -9876540; $this->rt['th2v'] = 'FLOAT';
		$this->rs['th3f'] = -9876540; $this->rt['th3f'] = 'FLOAT';
		$this->rs['th3g'] = -9876540; $this->rt['th3g'] = 'FLOAT';
		$this->rs['th3p'] = -9876540; $this->rt['th3p'] = 'FLOAT';
		$this->rs['th3c'] = -9876540; $this->rt['th3c'] = 'FLOAT';
		$this->rs['th4f'] = -9876540; $this->rt['th4f'] = 'FLOAT';
		$this->rs['th4p'] = -9876540; $this->rt['th4p'] = 'FLOAT';
		$this->rs['th4v'] = -9876540; $this->rt['th4v'] = 'FLOAT';
		$this->rs['thps'] = -9876540; $this->rt['thps'] = 'FLOAT';
		$this->rs['thsp'] = -9876540; $this->rt['thsp'] = 'FLOAT';
		$this->rs['thtg'] = -9876540; $this->rt['thtg'] = 'FLOAT';
		$this->rs['ti0p'] = -9876540; $this->rt['ti0p'] = 'FLOAT';
		$this->rs['ti0p2'] = -9876540; $this->rt['ti0p2'] = 'FLOAT';
		$this->rs['ti1p'] = -9876540; $this->rt['ti1p'] = 'FLOAT';
		$this->rs['ti1p2'] = -9876540; $this->rt['ti1p2'] = 'FLOAT';
		$this->rs['tl0p'] = -9876540; $this->rt['tl0p'] = 'FLOAT';
		$this->rs['tl0p2'] = -9876540; $this->rt['tl0p2'] = 'FLOAT';
		$this->rs['tl0v'] = -9876540; $this->rt['tl0v'] = 'FLOAT';
		$this->rs['tl1v'] = -9876540; $this->rt['tl1v'] = 'FLOAT';
		$this->rs['tl1p'] = -9876540; $this->rt['tl1p'] = 'FLOAT';
		$this->rs['tl2v'] = -9876540; $this->rt['tl2v'] = 'FLOAT';
		$this->rs['tlav'] = -9876540; $this->rt['tlav'] = 'FLOAT';
		$this->rs['tlbv'] = -9876540; $this->rt['tlbv'] = 'FLOAT';
		$this->rs['tlcv'] = -9876540; $this->rt['tlcv'] = 'FLOAT';
		$this->rs['tm0s'] = -9876540; $this->rt['tm0s'] = 'FLOAT';
		$this->rs['tm0p'] = -9876540; $this->rt['tm0p'] = 'FLOAT';
		$this->rs['tm0p2'] = -9876540; $this->rt['tm0p2'] = 'FLOAT';
		$this->rs['tm0p3'] = -9876540; $this->rt['tm0p3'] = 'FLOAT';
		$this->rs['tm0p4'] = -9876540; $this->rt['tm0p4'] = 'FLOAT';
		$this->rs['tm0r'] = -9876540; $this->rt['tm0r'] = 'FLOAT';
		$this->rs['tm1p'] = -9876540; $this->rt['tm1p'] = 'FLOAT';
		$this->rs['tm1r'] = -9876540; $this->rt['tm1r'] = 'FLOAT';
		$this->rs['tm2p'] = -9876540; $this->rt['tm2p'] = 'FLOAT';
		$this->rs['tm3p'] = -9876540; $this->rt['tm3p'] = 'FLOAT';
		$this->rs['tm4p'] = -9876540; $this->rt['tm4p'] = 'FLOAT';
		$this->rs['tm5p'] = -9876540; $this->rt['tm5p'] = 'FLOAT';
		$this->rs['tm6p'] = -9876540; $this->rt['tm6p'] = 'FLOAT';
		$this->rs['tm7p'] = -9876540; $this->rt['tm7p'] = 'FLOAT';
		$this->rs['tm8p'] = -9876540; $this->rt['tm8p'] = 'FLOAT';
		$this->rs['tm9p'] = -9876540; $this->rt['tm9p'] = 'FLOAT';
		$this->rs['tm1s'] = -9876540; $this->rt['tm1s'] = 'FLOAT';
		$this->rs['tm2s'] = -9876540; $this->rt['tm2s'] = 'FLOAT';
		$this->rs['tm3s'] = -9876540; $this->rt['tm3s'] = 'FLOAT';
		$this->rs['tm4s'] = -9876540; $this->rt['tm4s'] = 'FLOAT';
		$this->rs['tm5s'] = -9876540; $this->rt['tm5s'] = 'FLOAT';
		$this->rs['tm6s'] = -9876540; $this->rt['tm6s'] = 'FLOAT';
		$this->rs['tm7s'] = -9876540; $this->rt['tm7s'] = 'FLOAT';
        $this->rs['tm8s'] = -9876540; $this->rt['tm8s'] = 'FLOAT';
		$this->rs['tm9s'] = -9876540; $this->rt['tm9s'] = 'FLOAT';
		$this->rs['tmas'] = -9876540; $this->rt['tmas'] = 'FLOAT';
		$this->rs['tmbs'] = -9876540; $this->rt['tmbs'] = 'FLOAT';
		$this->rs['tmcs'] = -9876540; $this->rt['tmcs'] = 'FLOAT';
        $this->rs['tma1'] = -9876540; $this->rt['tma1'] = 'FLOAT';
		$this->rs['tma2'] = -9876540; $this->rt['tma2'] = 'FLOAT';
		$this->rs['tma3'] = -9876540; $this->rt['tma3'] = 'FLOAT';
		$this->rs['tma4'] = -9876540; $this->rt['tma4'] = 'FLOAT';
		$this->rs['tmap'] = -9876540; $this->rt['tmap'] = 'FLOAT';
		$this->rs['tmb1'] = -9876540; $this->rt['tmb1'] = 'FLOAT';
		$this->rs['tmb2'] = -9876540; $this->rt['tmb2'] = 'FLOAT';
		$this->rs['tmb3'] = -9876540; $this->rt['tmb3'] = 'FLOAT';
		$this->rs['tmb4'] = -9876540; $this->rt['tmb4'] = 'FLOAT';
		$this->rs['tmhs'] = -9876540; $this->rt['tmhs'] = 'FLOAT';
		$this->rs['tmlb'] = -9876540; $this->rt['tmlb'] = 'FLOAT';
		$this->rs['tmls'] = -9876540; $this->rt['tmls'] = 'FLOAT';
		$this->rs['tmps'] = -9876540; $this->rt['tmps'] = 'FLOAT';
		$this->rs['tmpv'] = -9876540; $this->rt['tmpv'] = 'FLOAT';
		$this->rs['tmtg'] = -9876540; $this->rt['tmtg'] = 'FLOAT';
		$this->rs['tn0c'] = -9876540; $this->rt['tn0c'] = 'FLOAT';
		$this->rs['tn0d'] = -9876540; $this->rt['tn0d'] = 'FLOAT';
		$this->rs['tn0h'] = -9876540; $this->rt['tn0h'] = 'FLOAT';
		$this->rs['th0n'] = -9876540; $this->rt['th0n'] = 'FLOAT';
		$this->rs['tn0p'] = -9876540; $this->rt['tn0p'] = 'FLOAT';
		$this->rs['tn1p'] = -9876540; $this->rt['tn1p'] = 'FLOAT';
		$this->rs['tnfp'] = -9876540; $this->rt['tnfp'] = 'FLOAT';
		$this->rs['tntg'] = -9876540; $this->rt['tntg'] = 'FLOAT';
		$this->rs['to0p'] = -9876540; $this->rt['to0p'] = 'FLOAT';
        $this->rs['tp0c'] = -9876540; $this->rt['tp0c'] = 'FLOAT';
		$this->rs['tp0d'] = -9876540; $this->rt['tp0d'] = 'FLOAT';
		$this->rs['tp0p'] = -9876540; $this->rt['tp0p'] = 'FLOAT';
		$this->rs['tp0p2'] = -9876540; $this->rt['tp0p2'] = 'FLOAT';
		$this->rs['tp0t'] = -9876540; $this->rt['tp0t'] = 'FLOAT';
		$this->rs['tp0g'] = -9876540; $this->rt['tp0g'] = 'FLOAT';
		$this->rs['tp1c'] = -9876540; $this->rt['tp1c'] = 'FLOAT';
		$this->rs['tp1g'] = -9876540; $this->rt['tp1g'] = 'FLOAT';
		$this->rs['tp1p'] = -9876540; $this->rt['tp1p'] = 'FLOAT';
		$this->rs['tp2g'] = -9876540; $this->rt['tp2g'] = 'FLOAT';
		$this->rs['tp2h'] = -9876540; $this->rt['tp2h'] = 'FLOAT';
		$this->rs['tp2p'] = -9876540; $this->rt['tp2p'] = 'FLOAT';
		$this->rs['tp3h'] = -9876540; $this->rt['tp3h'] = 'FLOAT';
		$this->rs['tp3p'] = -9876540; $this->rt['tp3p'] = 'FLOAT';
		$this->rs['tp3v'] = -9876540; $this->rt['tp3v'] = 'FLOAT';
		$this->rs['tp4p'] = -9876540; $this->rt['tp4p'] = 'FLOAT';
		$this->rs['tp5p'] = -9876540; $this->rt['tp5p'] = 'FLOAT';
		$this->rs['tpcd'] = -9876540; $this->rt['tpcd'] = 'FLOAT';
		$this->rs['tpps'] = -9876540; $this->rt['tpps'] = 'FLOAT';
		$this->rs['tptg'] = -9876540; $this->rt['tptg'] = 'FLOAT';
		$this->rs['ts0c'] = -9876540; $this->rt['ts0c'] = 'FLOAT';
		$this->rs['ts0g'] = -9876540; $this->rt['ts0g'] = 'FLOAT';
		$this->rs['ts0s'] = -9876540; $this->rt['ts0s'] = 'FLOAT';
		$this->rs['ts0p'] = -9876540; $this->rt['ts0p'] = 'FLOAT';
		$this->rs['ts0v'] = -9876540; $this->rt['ts0v'] = 'FLOAT';
		$this->rs['ts1p'] = -9876540; $this->rt['ts1p'] = 'FLOAT';
		$this->rs['ts1s'] = -9876540; $this->rt['ts1s'] = 'FLOAT';
		$this->rs['ts2p'] = -9876540; $this->rt['ts2p'] = 'FLOAT';
		$this->rs['ts2s'] = -9876540; $this->rt['ts2s'] = 'FLOAT';
		$this->rs['ts2v'] = -9876540; $this->rt['ts2v'] = 'FLOAT';
		$this->rs['ttf0'] = -9876540; $this->rt['ttf0'] = 'FLOAT';
		$this->rs['ttld'] = -9876540; $this->rt['ttld'] = 'FLOAT';
		$this->rs['ttrd'] = -9876540; $this->rt['ttrd'] = 'FLOAT';
		$this->rs['tw0p'] = -9876540; $this->rt['tw0p'] = 'FLOAT';
		$this->rs['tw0p2'] = -9876540; $this->rt['tw0p2'] = 'FLOAT';
        		
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;

        // Indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('ta0p');
        $this->idx[] = array('tc0f');
        $this->idx[] = array('tc0d');
        $this->idx[] = array('tc0p');
        $this->idx[] = array('tb0t');
        $this->idx[] = array('tb1t');
        $this->idx[] = array('tb2t');
        $this->idx[] = array('tg0d');
        $this->idx[] = array('tg0h');
        $this->idx[] = array('tg0p');
        $this->idx[] = array('th0p');
        $this->idx[] = array('th0h');
        $this->idx[] = array('th1h');
        $this->idx[] = array('th2h');
        $this->idx[] = array('tm0s');
        $this->idx[] = array('tm0p');
        $this->idx[] = array('ts0p');
        $this->idx[] = array('tl0p');
        $this->idx[] = array('tm0p4');
        $this->idx[] = array('tn0h');
        $this->idx[] = array('tn0d');
        $this->idx[] = array('tn0p');
        $this->idx[] = array('tp0p');
        $this->idx[] = array('discin');
        $this->idx[] = array('fan_0');
        $this->idx[] = array('fan_1');
        $this->idx[] = array('fan_2');
        $this->idx[] = array('fan_3');
        $this->idx[] = array('fan_4');
        $this->idx[] = array('fan_5');
        $this->idx[] = array('fan_6');
        $this->idx[] = array('fan_7');
        $this->idx[] = array('fan_8');
        $this->idx[] = array('fanmin0');
        $this->idx[] = array('fanmin1');
        $this->idx[] = array('fanmin2');
        $this->idx[] = array('fanmin3');
        $this->idx[] = array('fanmin4');
        $this->idx[] = array('fanmin5');
        $this->idx[] = array('fanmin6');
        $this->idx[] = array('fanmin7');
        $this->idx[] = array('fanmin8');
        $this->idx[] = array('fanmax0');
        $this->idx[] = array('fanmax1');
        $this->idx[] = array('fanmax2');
        $this->idx[] = array('fanmax3');
        $this->idx[] = array('fanmax4');
        $this->idx[] = array('fanmax5');
        $this->idx[] = array('fanmax6');
        $this->idx[] = array('fanmax7');
        $this->idx[] = array('fanmax8');
        $this->idx[] = array('fanlabel0');
        $this->idx[] = array('fanlabel1');
        $this->idx[] = array('fanlabel2');
        $this->idx[] = array('fanlabel3');
        $this->idx[] = array('fanlabel4');
        $this->idx[] = array('fanlabel5');
        $this->idx[] = array('fanlabel6');
        $this->idx[] = array('fanlabel7');
        $this->idx[] = array('fanlabel8');
        
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_record($serial);
		}
		
		$this->serial = $serial;
    }

    /**
     * Process data sent by postflight
     *
     * @param plist data
     * @author tuxudo
     * https://github.com/beltex/SMCKit/
     **/
    function process($data)
    {
        // If data is empty, throw error
        if (! $data) {
            throw new Exception("Error Processing Fan_Temps Module Request: No data found", 1);
        }
        
        // Translate fan_temp strings to db fields
        $translate = array(
        	'TA0P' => 'ta0p',
        	'TA1P' => 'ta1p',
        	'TC0F' => 'tc0f',
        	'TC0D' => 'tc0d',
        	'TC0H' => 'tc0h',
        	'TC0P' => 'tc0p',
        	'TB0T' => 'tb0t',
        	'TB1T' => 'tb1t',
        	'TB2T' => 'tb2t',
        	'TB3T' => 'tb3t',
        	'TG0D' => 'tg0d',
        	'TG0H' => 'tg0h',
        	'TG0P' => 'tg0p',
        	'TH0P' => 'th0p',
        	'Th0H' => 'th0h',
        	'Th1H' => 'th1h',
        	'Th2H' => 'th2h',
        	'TM0S' => 'tm0s',
        	'TM0P' => 'tm0p',
        	'Ts0P' => 'ts0p',
        	'TL0P' => 'tl0p',
        	'Tm0P' => 'tm0p4',
        	'TN0H' => 'tn0h',
        	'TN0D' => 'tn0d',
        	'TN0P' => 'tn0p',
        	'TO0P' => 'to0p',
        	'Tp0P' => 'tp0p',
        	'TI0P' => 'ti0p',
        	'TI1P' => 'ti1p',
        	'TA0p' => 'ta0p2',
        	'TA1p' => 'ta1p2',
        	'TBXT' => 'tbxt',
        	'TC0C' => 'tc0c2',
        	'TC0E' => 'tc0e',
        	'TC0G' => 'tc0g',
        	'TC0J' => 'tc0j',
        	'TC0c' => 'tc0c',
        	'TC0d' => 'tc0d2',
        	'TC0p' => 'tc0p2',
        	'TC1C' => 'tc1c',
        	'TC1c' => 'tc1c2',
        	'TC2C' => 'tc2c',
        	'TC2c' => 'tc2c2',
        	'TC3C' => 'tc3c',
        	'TC3c' => 'tc3c2',
        	'TC4C' => 'tc4c',
        	'TCAC' => 'tcac',
        	'TCAD' => 'tcad',
        	'TCAG' => 'tcag',
        	'TCAH' => 'tcah',
        	'TCAS' => 'tcas',
        	'TCBC' => 'tcbc',
        	'TCBD' => 'tcbd',
        	'TCBG' => 'tcbg',
        	'TCBH' => 'tcbh',
        	'TCBS' => 'tcbs',
        	'TCFP' => 'tcfp',
        	'TCGC' => 'tcgc',
        	'TCGc' => 'tcgc2',
        	'TCPG' => 'tcpg',
        	'TCSA' => 'tcsa',
        	'TCSC' => 'tcsc',
        	'TCSc' => 'tcsc2',
        	'TCTD' => 'tctd',
        	'TCXC' => 'tcxc',
        	'TCXc' => 'tcxc2',
        	'TCXr' => 'tcsr',
        	'TG0p' => 'tg0p2',
        	'TG1D' => 'tg1d',
        	'TG1F' => 'tg1f',
        	'TG1d' => 'tg1d2',
        	'TGTC' => 'tgtc',
        	'TGTD' => 'tgtd',
        	'TH0A' => 'th0a',
        	'TH0B' => 'th0b',
        	'TH0C' => 'th0c',
        	'TH0F' => 'th0f',
        	'TH0O' => 'th0o',
        	'TH0R' => 'th0r',
        	'TH0V' => 'th0v',
        	'TH0X' => 'th0x',
        	'TH0a' => 'th0a2',
        	'TH0b' => 'th0b2',
        	'TH0c' => 'th0c2',
        	'TH0x' => 'th0x2',
        	'TH1A' => 'th1a',
        	'TH1B' => 'th1b',
        	'TH1C' => 'th1c',
        	'TH1F' => 'th1f',
        	'TH1O' => 'th1o',
        	'TH1P' => 'th1p',
        	'TH1V' => 'th1v',
        	'TH1X' => 'th1x',
        	'TH1a' => 'th1a2',
        	'TH1b' => 'th1b2',
        	'TH1c' => 'th1c2',
        	'TH2F' => 'th2f',
        	'TH2P' => 'th2p',
        	'TH2V' => 'th2v',
        	'TH3F' => 'th3f',
        	'TH3P' => 'th3p',
        	'TH3V' => 'th3c',
        	'TH4F' => 'th4f',
        	'TH4P' => 'th4p',
        	'TH4V' => 'th4v',
        	'THPS' => 'thps',
        	'THSP' => 'thsp',
        	'THTG' => 'thtg',
        	'TI0p' => 'ti0p2',
        	'TI1p' => 'ti1p2',
        	'TL0V' => 'tl0v',
        	'TL0p' => 'tl0p2',
        	'TL1V' => 'tl1v',
        	'TL2V' => 'tl2v',
        	'TLAV' => 'tlav',
        	'TLBV' => 'tlbv',
        	'TLCV' => 'tlcv',
        	'TM0p' => 'tm0p2',
        	'TM1P' => 'tm1p',
        	'TM2P' => 'tm2p',
        	'TM3P' => 'tm3p',
        	'TM4P' => 'tm4p',
        	'TM5P' => 'tm5p',
        	'TM6P' => 'tm6p',
        	'TM7P' => 'tm7p',
        	'TM8P' => 'tm8p',
        	'TMA1' => 'tma1',
        	'TMA2' => 'tma2',
        	'TMA3' => 'tma3',
        	'TMA4' => 'tma4',
        	'TMB1' => 'tmb1',
        	'TMB2' => 'tmb2',
        	'TMB3' => 'tmb3',
        	'TMB4' => 'tmb4',
        	'TMBS' => 'tmbs',
        	'TMHS' => 'tmhs',
        	'TMLB' => 'tmlb',
        	'TMLS' => 'tmls',
        	'TMPS' => 'tmps',
        	'TMPV' => 'tmpv',
        	'TMTG' => 'tmtg',
        	'TNFP' => 'tnfp',
        	'TNTG' => 'tntg',
        	'TO0p' => 'to0p2',
        	'TP0p' => 'tp0p2',
        	'TPCD' => 'tpcd',
        	'TS0V' => 'ts0v',
        	'TS2V' => 'ts2v',
        	'TTF0' => 'ttf0',
        	'TW0P' => 'tw0p',
        	'TW0p' => 'tw0p2',
        	'Te1F' => 'te1f',
        	'Te1P' => 'te1p',
        	'Te1S' => 'te1s',
        	'Te2F' => 'te2f',
        	'Te2S' => 'te2s',
        	'Te3F' => 'te3f',
        	'Te3S' => 'te3s',
        	'Te4F' => 'te4f',
        	'Te4S' => 'te4s',
        	'Te5F' => 'te5f',
        	'Te5S' => 'te5s',
        	'TeGG' => 'tegg',
        	'TeGP' => 'tegp',
        	'TeRG' => 'terg',
        	'TeRP' => 'tetp',
        	'Tm0p' => 'tm0p3',
        	'Tp0C' => 'tp0c',
        	'Tp1C' => 'tp1c',
        	'Tp1P' => 'tp1p',
        	'Tp2H' => 'tp2h',
        	'Tp3H' => 'tp3h',
        	'Tp3v' => 'tp3v',
        	'TpPS' => 'tpps',
        	'TpTG' => 'tptg',
        	'Ts0G' => 'ts0g',
        	'Ts0S' => 'ts0s',
        	'Ts1P' => 'ts1p',
        	'Ts1S' => 'ts1s',
        	'Ts2S' => 'ts2s',
        	'Tsqf' => 'tsqf',
        	'TA0S' => 'ta0s',
        	'TA1S' => 'ta1s',
        	'TA2S' => 'ta2s',
        	'TA3S' => 'ta3s',
        	'Tb0P' => 'tb0p',
        	'TC1D' => 'tc1d',
        	'TC1E' => 'tc1e',
        	'TC1F' => 'tc1f',
        	'TC1H' => 'tc1h',
        	'TC1P' => 'tc1p',
        	'TC5C' => 'tc5c',
        	'TC6C' => 'tc6c',
        	'TC7C' => 'tc7c',
        	'TC8C' => 'tc8c',
        	'TCHP' => 'tchp',
        	'TG1H' => 'tg1h',
        	'TM1S' => 'tm1s',
        	'TM8S' => 'tm8s',
        	'TM9P' => 'tm9p',
        	'TM9S' => 'tm9s',
        	'TN0C' => 'tn0c',
        	'TN1P' => 'tn1p',
        	'TP0D' => 'tp0d',
        	'Tp2P' => 'tp2p',
        	'Tp3P' => 'tp3p',
        	'Tp4P' => 'tp4p',
        	'Tp5P' => 'tp5p',
        	'TS0C' => 'ts0c',
        	'TL1p' => 'tl1p',
        	'TGVP' => 'tgvp',
        	'TaRC' => 'tarc',
        	'TaLC' => 'talc',
        	'TTRD' => 'ttrd',
        	'TTLD' => 'ttld',
        	'Th0N' => 'th0n',
        	'TS2P' => 'ts2p',
        	'TG0T' => 'tg0t',
        	'TC2P' => 'tc2p',
        	'TC3P' => 'tc3p',
        	'TG0C' => 'tg0c',
        	'TA2p' => 'ta2p',
        	'TG0r' => 'tg0r',
        	'TG1p' => 'tg1p',
        	'TG1r' => 'tg1r',
        	'TM0r' => 'tm0r',
        	'TM1r' => 'tm1r',
        	'Te0t' => 'te0t',
        	'Tp0t' => 'tp0t',
        	'TMAP' => 'tamp',
        	'TH1R' => 'th1r',
        	'TA0G' => 'ta0g',
        	'TA2G' => 'ta2g',
        	'TA4S' => 'ta4s',
        	'TA5S' => 'ta5s',
        	'TC2D' => 'tc2d',
        	'TG0G' => 'tg0g',
        	'TH1G' => 'th1g',
        	'TH2G' => 'th2g',
        	'TH3G' => 'th3g',
        	'TM2S' => 'tm2s',
        	'TM3S' => 'tm3s',
        	'TM4S' => 'tm4s',
        	'TM5S' => 'tm5s',
        	'TM6S' => 'tm6s',
        	'TM7S' => 'tm7s',
        	'TMAS' => 'tmas',
        	'TMCS' => 'tmcs',
        	'Tp0G' => 'tp0g',
        	'Tp1G' => 'tp1g',
        	'Tp2G' => 'tp2g',
        	'FAN_0_Label' => 'fanlabel0',
        	'FAN_1_Label' => 'fanlabel1',
        	'FAN_2_Label' => 'fanlabel2',
        	'FAN_3_Label' => 'fanlabel3',
        	'FAN_4_Label' => 'fanlabel4',
        	'FAN_5_Label' => 'fanlabel5',
        	'FAN_6_Label' => 'fanlabel6',
        	'FAN_7_Label' => 'fanlabel7',
        	'FAN_8_Label' => 'fanlabel8',
        	'FAN_0_Current' => 'fan_0',
        	'FAN_1_Current' => 'fan_1',
        	'FAN_2_Current' => 'fan_2',
        	'FAN_3_Current' => 'fan_3',
        	'FAN_4_Current' => 'fan_4',
        	'FAN_5_Current' => 'fan_5',
        	'FAN_6_Current' => 'fan_6',
        	'FAN_7_Current' => 'fan_7',
        	'FAN_8_Current' => 'fan_8',
        	'FAN_0_Min' => 'fanmin0',
        	'FAN_1_Min' => 'fanmin1',
        	'FAN_2_Min' => 'fanmin2',
        	'FAN_3_Min' => 'fanmin3',
        	'FAN_4_Min' => 'fanmin4',
        	'FAN_5_Min' => 'fanmin5',
        	'FAN_6_Min' => 'fanmin6',
        	'FAN_7_Min' => 'fanmin7',
        	'FAN_8_Min' => 'fanmin8',
        	'FAN_0_Max' => 'fanmax0',
        	'FAN_1_Max' => 'fanmax1',
        	'FAN_2_Max' => 'fanmax2',
        	'FAN_3_Max' => 'fanmax3',
        	'FAN_4_Max' => 'fanmax4',
        	'FAN_5_Max' => 'fanmax5',
        	'FAN_6_Max' => 'fanmax6',
        	'FAN_7_Max' => 'fanmax7',
        	'FAN_8_Max' => 'fanmax8',
        	'DiscIn' => 'discin'
        );
        
        // Array of string for nulling with ""
        $strings =  array('discin','fanlabel0','fanlabel1','fanlabel2','fanlabel3','fanlabel4','fanlabel5','fanlabel6','fanlabel7','fanlabel8');
        
        // Array of fans to protect from nulling if zero
        $fans =  array('fanl0','fan_1','fan_2','fan_3','fan_4','fan_5','fan_6','fan_7','fan_8');
        
        // Process incoming fan_temps.xml
        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);
        $plist = $parser->toArray();
        
        // Traverse the xml with translations
        foreach ($translate as $search => $field) {  
                // If key is not empty, not a string, is not a fan, and less then 10 null it to fake null of -9876540
                if (! empty($plist[$search]) && ! in_array($field, $strings) && ! in_array($field, $fans) && $plist[$search] <= 10 ) {  
                        $this->$field = -9876540;
                    
                // Else if key is not empty save it to the object
                } else if (! empty($plist[$search])) {  
                        $this->$field = $plist[$search];
                    
                // Else, check if key is an int  
                } else {
                    if ( ! in_array($field, $strings) && $plist[$search] != "0"){
                        // Set the int to fake null of -9876540
                        $this->$field = -9876540;
                    } else if ( ! in_array($field, $strings) && $plist[$search] == "0"){
                        // Set the int to 0
                        $this->$field = $plist[$search];
                    } else {  
                        // Else, null the value
                        $this->$field = '';
                    }
                }
            }
    
		$this->save();
    }
}