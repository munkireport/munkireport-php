<?php
class show extends Controller
{
	function __construct()
	{
		check_db();
	} 
	
	function index()
	{
		$client = new Client();
				
		$order = " ORDER BY name ASC";
		
        $data['error_clients'] = $client->retrieve_many('errors > 0'.$order);
		$data['warning_clients'] = $client->retrieve_many('warnings > 0'.$order);
        $data['activity_clients'] = $client->retrieve_many('activity != ""'.$order);

		$obj = new View();
		$obj->view('overview',$data);

	}
	
	function reports()
	{
		$client = new Client();
		
		$data['objects'] = $client->retrieve_many('id > 0 ORDER BY name ASC');
		
		$obj = new View();
		$obj->view('client_list',$data);
		
	}
	
	function client_list()
	{
		$this->reports();
		
	}

	function report($serial = '')
	{
		$client = new Client($serial);
		
		$report = $client->report_plist;
		
		// Move install results over to their install items.
		$install_results = array();
		if(isset($report['InstallResults']))
		{
			foreach($report['InstallResults'] as $result)
			{
				$install_results[$result["name"] . '-' .$result["version"]] = 
					array('result' => $result["status"] == 0 ? 'Installed' : 'error '. $result['status']);
			}
		}
		if(isset($report['ItemsToInstall']))
		{
			foreach($report['ItemsToInstall'] as $key => &$item)
			{
				$item['install_result'] = 'Pending';
				$dversion = $item["display_name"].'-'.$item["version_to_install"];
				if(isset($install_results[$dversion]))
				{
					$report['ItemsToInstall'][$key]['install_result'] = $install_results[$dversion]['result'];
				}
			}
			
		}
		if(isset($report['AppleUpdates']))
		{
			foreach($report['AppleUpdates'] as $key => $item)
			{
				$item['install_result'] = 'Pending';
				$dversion = $item["display_name"].'-'.$item["version_to_install"];
				if(isset($install_results[$dversion]))
				{
					$report['AppleUpdates'][$key]['install_result'] = $install_results[$dversion]['result']; 
				}
			}
			
		}
        
		/* todo: removal results: see below
        
        # Move removal results over to their removal items.
        removal_results = dict()
        if "RemovalResults" in report:
            for result in report["RemovalResults"]:
                m = re_result.search(result)
                if m:
                    removal_results[m.group("dname")] = {
                        "result": "Removed" if m.group("result") == "SUCCESSFUL" else m.group("result")
                    }
        if "ItemsToRemove" in report:
            for item in report["ItemsToRemove"]:
                item["removal_result"] = "Pending"
                dversion = item["display_name"]
                if dversion in removal_results:
                    res = removal_results[dversion]
                    item["removal_result"] = res["result"]
        
        return dict(
            page="reports",
            client=client,
            report=report,
            install_results=install_results,
            removal_results=removal_results
        */
		
		$data['client'] = $client;
		$data['report'] = $report;
		$data['install_results'] = $install_results;
		
		//echo '<pre>';
		
		//print_r($install_results);
		
		
		$obj = new View();
		$obj->view('report',$data);
		
	}
	
}