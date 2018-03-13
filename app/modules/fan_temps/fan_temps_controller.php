<?php 

/**
 * Fan_temps_controller class
 *
 * @package fan_temps
 * @author tuxudo
 **/
class Fan_temps_controller extends Module_controller
{
	function __construct()
	{
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the fan_temps module!";
	}
    
    /**
     * Retrieve data in json format for client temps tab
     *
     * @return void
     * @author tuxudo
     **/
    public function get_temp_tab_data($serial_number = '')
    {        
        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new Fan_temps_model();
        
        $sql = "SELECT ta0p, ta1p, ta2p, ta0p2, ta1p2, ta0g, ta2g, ta0s, ta1s, ta2s, ta3s, ta4s, ta5s, talc, tarc, tb0p, tb0t, tb1t, tb2t, tb3t, tbxt, tc0c, tc0d, tc0d2, tc0e, tc0f, tc0g, tc0h, tc0j, tc0p, tc0p2, tc1c, tc1d, tc1e, tc1f, tc1h, tc1p, tc2c, tc2p, tc3c, tc3p, tc4c, tc5c, tc6c, tc7c, tc8c, tc0c2, tc1c2, tc2c2, tc3c2, tc2d, tcac, tcad, tcag, tcah, tcas, tcbc, tcbd, tcbg, tcbh, tcbs, tcfp, tcgc, tcgc2, tchp, tcpg, tcsa, tcsc, tcsc2, tctd, tcxc, tcxc2, tcsr, te0t, te1f, te1p, te1s, te2f, te2s, te3f, te3s, te4f, te4s, te5f, te5s, tegg, tegp, terg, tetp, tg0d, tg0g, tg0h, tg0p, tg0p2, tg0r, tg0t, tg1d, tg1f, tg1h, tg1d2, tg1p, tg1r, tgtc, tgtd, tgvp, th0a, th0a2, th0b, th0b2, th0c, th0c2, th0f, th0h, th0o, th0p, th0r, th0v, th0x, th0x2, th1a, th1a2, th1b, th1b2, th1c, th1c2, th1f, th1g, th1h, th1o, th1p, th1r, th1v, th1x, th2f, th2g, th2h, th2p, th2v, th3f, th3g, th3p, th3c, th4f, th4p, th4v, thps, thsp, thtg, ti0p, ti0p2, ti1p, ti1p2, tl0p, tl0p2, tl0v, tl1v, tl1p, tl2v, tlav, tlbv, tlcv, tm0s, tm0p, tm0p2, tm0p3, tm0p4, tm0r, tm1p, tm1r, tm2p, tm3p, tm4p, tm5p, tm6p, tm7p, tm8p, tm9p, tm1s, tm2s, tm3s, tm4s, tm5s, tm6s, tm7s, tm8s, tm9s, tmas, tmbs, tmcs, tma1, tma2, tma3, tma4, tmap, tmb1, tmb2, tmb3, tmb4, tmhs, tmlb, tmls, tmps, tmpv, tmtg, tn0c, tn0d, tn0h, th0n, tn0p, tn1p, tnfp, tntg, to0p, tp0c, tp0d, tp0p, tp0p2, tp0t, tp0g, tp1c, tp1g, tp1p, tp2g, tp2h, tp2p, tp3h, tp3p, tp3v, tp4p, tp5p, tpcd, tpps, tptg, ts0c, ts0g, ts0s, ts0p, ts0v, ts1p, ts1s, ts2p, ts2s, ts2v, ttf0, ttld, ttrd, tw0p, tw0p2 FROM fan_temps WHERE serial_number = '$serial_number'";
        
        $fan_temps_tab = $queryobj->query($sql);

        // Add the temperature type to the object for the client tab
        $fan_temps_tab[0]->temperature_unit = conf('temperature_unit');
            
        $obj->view('json', array('msg' => current(array('msg' => $fan_temps_tab)))); 
    }
    
     /**
     * Retrieve data in json format for client smc tab
     *
     * @return void
     * @author tuxudo
     **/
    public function get_smc_tab_data($serial_number = '')
    {        
        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new Fan_temps_model();
        
        $sql = "SELECT aupo, alsl, msac, msag, msam, msha, msld, hdbs, mssd, mssp, mstc, mstg, mstm, bnum, natj, nati, discin, mstm, lsof, lspv, mo_x, mo_y, mo_z, spht, sph0, sght, sgtt, sctg, sgtg, shtg, sltg, sltp, sotg, sptg, slpt, slst, sppt, spst FROM fan_temps WHERE serial_number = '$serial_number'";
        
        $fan_temps_tab = $queryobj->query($sql);
        
        // Add the temperature type to the object for the client tab
        $fan_temps_tab[0]->temperature_unit = conf('temperature_unit');
            
        $obj->view('json', array('msg' => current(array('msg' => $fan_temps_tab)))); 
    }
    
     /**
     * Retrieve data in json format for client amps tab
     *
     * @return void
     * @author tuxudo
     **/
    public function get_amps_tab_data($serial_number = '')
    {       
        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new Fan_temps_model();
        
        $sql = "SELECT ibac, ib0p, ib0l, ib0r, ipbf, icmc, ipbr, icac, icbc, ics0, ic0c, ic1c, ic2c, ic3c, ic4c, ic5c, ic6c, ic7c, ic3c, ic5r, ic0g, icgc, icgm, icam, ics1, imcc, ic0m, ic8r, ic0r, icec, icsc, im3c, ig0r, ig0c, ig1c, ig0f, ih1z, ih2z, ih3z, ih4z, ih05, ih02, ikbc, ilcd, iblc, iblr, im1c, i18c, id2r, id0r, id5r, im0r, im0c, imas, imbs, io0r, io3r, io5r, ie1s, ie2s, ie3s, ie4s, ieas, iebs, in0c, iscc, ip0c, icmc, ihir, ihcc, ihnc, is1c, if3c, if5c, iidc, ihsc, iulc, iurc, itpc, itar, it3c, iapc, ibtc, pb0r, pblc, pcac, pcbc, pcam, pc0c, pc1c, pc2c, pc3c, pc4c, pc5c, pc6c, pc7c, pcpd, pcpg, pcfc, pcgm, pmcc, pcpc, ptgr, pcpr, pc1r, pc5r, pcpl, pcpt, pctr, pcec, pcsc, pd0r, pg0r, pg0c, pgtr, ph1z, ph2z, ph3z, ph4z, ph02, ph05, pd2r, pp0r, pc0r, pd0r, pd5r, pdmr, pm0r, pmas, pmbs, ppsm, pm0c, pm1c, po0r, phnc, po3r, po5r, ppbr, pn1r, pn0c, pe1s, pe2s, pe3s, pe4s, peas, pebs, pp0c, ptar, ps1c, phcc, pdtr, pstr, pthc, phpc, v1r1, vacc, vv7s, vbat, vb0r, vc1c, vcs0, vcac, vcbc, vc0c, vc1c, vc2c, vc3c, vc4c, vc5c, vc6c, vc7c, vc0g, vn0c, vc0m, vs2c, vcgc, vcsc, vv1r, vcfr, vg0r, vg0c, vg1c, vg0f, vv2s, vr3r, vs8c, vh05, vv1s, vp0r, vdr2, vv9s, vd8r, vd5r, vm0r, vmas, vmbs, vn1r FROM fan_temps WHERE serial_number = '$serial_number'";
        
        $fan_temps_tab = $queryobj->query($sql);
            
        $obj = new View();
        $obj->view('json', array('msg' => current(array('msg' => $fan_temps_tab)))); 
    }
    
    /**
     * Retrieve data in json format for client fans tab
     *
     * @return void
     * @author tuxudo
     **/
    public function get_fan_tab_data($serial_number = '')
    {        
        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new Fan_temps_model();
        
        $sql = "SELECT fnum, fnfd, mssf, fanlabel0, fan_0, fanmin0, fanmax0, dba0, fanlabel1, fan_1, fanmin1, fanmax1, dba1, fanlabel2, fan_2, fanmin2, fanmax2, dba2, fanlabel3, fan_3, fanmin3, fanmax3, fanlabel4, fan_4, fanmin4, fanmax4, fanlabel5, fan_5, fanmin5, fanmax5, fanlabel6, fan_6, fanmin6, fanmax6, fanlabel7, fan_7, fanmin7, fanmax7, fanlabel8, fan_8, fanmin8, fanmax8, fan_9, fanmin9, fanmax9, fan_10, fanmin10, fanmax10, fan_11, fanmin11, fanmax11, fan_12, fanmin12, fanmax12, fan_13, fanmin13, fanmax13, fan_14, fanmin14, fanmax14, fan_15, fanmin15, fanmax15, fan_16, fanmin16, fanmax16, fan_17, fanmin17, fanmax17, dbah, dbat FROM fan_temps WHERE serial_number = '$serial_number'";
        
        $fan_temps_tab = $queryobj->query($sql);
            
        $obj = new View();
        $obj->view('json', array('msg' => current(array('msg' => $fan_temps_tab)))); 
    }
    
     /**
     * Retrieve data in json format for client tab
     *
     * @return void
     * @author tuxudo
     **/
    public function get_client_tab_data($serial_number = '')
    {        
        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new Fan_temps_model();
        
        $sql = "SELECT * FROM fan_temps WHERE serial_number = '$serial_number'";
        
        $fan_temps_tab = $queryobj->query($sql);

        // Add the temperature type to the object for the client tab
        $fan_temps_tab[0]->temperature_unit = conf('temperature_unit');
            
        $obj->view('json', array('msg' => current(array('msg' => $fan_temps_tab)))); 
    }

     /**
     * Retrieve data in json format
     *
     * @return void
     * @author tuxudo
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $fan_temps = new Fan_temps_model;
        $obj->view('json', array('msg' => $fan_temps->retrieve_records($serial_number)));
    }
} // END class Fan_temps_controller
