<?php

// Remove fake nulls and set them to NULL

class Migration_fan_temps_remove_fake_null extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'fan_temps';
    }

    public function up()
    {
        // Set Nulls
         foreach (array('fan_0','fan_1','fan_2','fan_3','fan_4','fan_5','fan_6','fan_7','fan_8','fanmin0','fanmin1','fanmin2','fanmin3','fanmin4','fanmin5','fanmin6','fanmin7','fanmin8','fanmax0','fanmax1','fanmax2','fanmax3','fanmax4','fanmax5','fanmax6','fanmax7','fanmax8','ta0p','ta1p','ta2p','ta0p2','ta1p2','ta0g','ta2g','ta0s','ta1s','ta2s','ta3s','ta4s','ta5s','talc','tarc','tb0p','tb0t','tb1t','tb2t','tb3t','tbxt','tc0c','tc0d','tc0d2','tc0e','tc0f','tc0g','tc0h','tc0j','tc0p','tc0p2','tc1c','tc1d','tc1e','tc1f','tc1h','tc1p','tc2c','tc2p','tc3c','tc3p','tc4c','tc5c','tc6c','tc7c','tc8c','tc0c2','tc1c2','tc2c2','tc3c2','tc2d','tcac','tcad','tcag','tcah','tcas','tcbc','tcbd','tcbg','tcbh','tcbs','tcfp','tcgc','tcgc2','tchp','tcpg','tcsa','tcsc','tcsc2','tctd','tcxc','tcxc2','tcsr','te0t','te1f','te1p','te1s','te2f','te2s','te3f','te3s','te4f','te4s','te5f','te5s','tegg','tegp','terg','tetp','tg0d','tg0g','tg0h','tg0p','tg0p2','tg0r','tg0t','tg1d','tg1f','tg1h','tg1d2','tg1p','tg1r','tgtc','tgtd','tgvp','th0a','th0a2','th0b','th0b2','th0c','th0c2','th0f','th0h','th0o','th0p','th0r','th0v','th0x','th0x2','th1a','th1a2','th1b','th1b2','th1c','th1c2','th1f','th1g','th1h','th1o','th1p','th1r','th1v','th1x','th2f','th2g','th2h','th2p','th2v','th3f','th3g','th3p','th3c','th4f','th4p','th4v','thps','thsp','thtg','ti0p','ti0p2','ti1p','ti1p2','tl0p','tl0p2','tl0v','tl1v','tl1p','tl2v','tlav','tlbv','tlcv','tm0s','tm0p','tm0p2','tm0p3','tm0p4','tm0r','tm1p','tm1r','tm2p','tm3p','tm4p','tm5p','tm6p','tm7p','tm8p','tm9p','tm1s','tm2s','tm3s','tm4s','tm5s','tm6s','tm7s','tm8s','tm9s','tmas','tmbs','tmcs','tma1','tma2','tma3','tma4','tmap','tmb1','tmb2','tmb3','tmb4','tmhs','tmlb','tmls','tmps','tmpv','tmtg','tn0c','tn0d','tn0h','th0n','tn0p','tn1p','tnfp','tntg','to0p','tp0c','tp0d','tp0p','tp0p2','tp0t','tp0g','tp1c','tp1g','tp1p','tp2g','tp2h','tp2p','tp3h','tp3p','tp3v','tp4p','tp5p','tpcd','tpps','tptg','ts0c','ts0g','ts0s','ts0p','ts0v','ts1p','ts1s','ts2p','ts2s','ts2v','ttf0','ttld','ttrd','tw0p','tw0p2') as $item)
        {    
            $sql = 'UPDATE fan_temps 
            SET '.$item.' = NULL
            WHERE '.$item.' = -9876543 OR '.$item.' = -9876540';
            $this->exec($sql);
        }
    }

    public function down()
    {
        throw new Exception("Can't go back", 1);
    }
}
