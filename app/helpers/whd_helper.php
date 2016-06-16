<?php

/**
 *
 * @param object gsx model instance
 * @author John Eberle (tuxudo)
 **/

function run_whd_status(&$whd_model)
{
	// Error message
	$error = '';
    
       // Setup variables and import httpful - http://phphttpclient.com/
       require_once (conf('application_path').'lib/httpful/httpful.phar');
       $api_key = conf('whd_api_key');
       $whd_uri = conf('whd_uri');
       $uri = ''.($whd_uri).'/ra/Assets.json?apiKey='.($api_key).'&qualifier=(serialNumber=\''.($whd_model->serial_number).'\')&style=details';
    
       // Get JSON response
       $response = \Httpful\Request::get($uri)->send();
       $objresponse = $response->body[0];
    
       // If WHD lookup failed
       if(is_null($objresponse))
       {
           $whd_model->assetNumber = 'WHD Lookup Failed';
           $whd_model->notes = 'WHD lookup failed or machine is not in WHD';
           $whd_model->save();
           $error = 'Web Help Desk asset lookup failed or machine is not in Web Help Desk';
       }
       
       else 
       {
           // Translate network strings to db fields
           $whd_model->assetNumber = utf8_encode($objresponse->assetNumber);
           $whd_model->notes = utf8_encode($objresponse->notes);
           $whd_model->version = utf8_encode($objresponse->version);
           $whd_model->roomName = utf8_encode($objresponse->room->roomName);
           $whd_model->modelName = utf8_encode($objresponse->model->modelName);
           $whd_model->locationName = utf8_encode($objresponse->location->locationName);
           $whd_model->macAddress = utf8_encode($objresponse->macAddress);
           $whd_model->networkAddress = utf8_encode($objresponse->networkAddress);
           $whd_model->networkName = utf8_encode($objresponse->networkName);
           $whd_model->purchaseDate = utf8_encode($objresponse->purchaseDate);
           $whd_model->locationid = utf8_encode($objresponse->location->id);
           $whd_model->address = utf8_encode($objresponse->location->address);
           $whd_model->city = utf8_encode($objresponse->location->city);
           $whd_model->postalCode = utf8_encode($objresponse->location->postalCode);
           $whd_model->state = utf8_encode($objresponse->location->state);
           $whd_model->assetType = utf8_encode($objresponse->model->assettype->assetType);
           $whd_model->client = utf8_encode($objresponse->clients[0]->id);
           $whd_model->isReservable = utf8_encode($objresponse->isReservable);
           $whd_model->leaseExpirationDate = utf8_encode($objresponse->leaseExpirationDate);
           $whd_model->warrantyExpirationDate = utf8_encode($objresponse->warrantyExpirationDate);
           $whd_model->isNotesVisibleToClients = utf8_encode($objresponse->isNotesVisibleToClients);
           $whd_model->isDeleted = utf8_encode($objresponse->isDeleted);

           // Get client data
           $client_uri = ''.($whd_uri).'/ra/Clients/'.($whd_model->client).'?apiKey='.($api_key);
           $client_response = \Httpful\Request::get($client_uri)->send();
           $clientobjresponse = $client_response->body;
           
           // Process client data
           $whd_model->clientEmail = utf8_encode($clientobjresponse->email);
           $whd_model->clientName = utf8_encode($clientobjresponse->firstName).' '.utf8_encode($clientobjresponse->lastName);
           $whd_model->clientNotes = utf8_encode($clientobjresponse->notes);
           $whd_model->clientPhone = utf8_encode($clientobjresponse->phone);
           $whd_model->clientPhone2 = utf8_encode($clientobjresponse->phone2);
           $whd_model->clientdepartment = utf8_encode($clientobjresponse->department->name);
           $whd_model->clientaddress = utf8_encode($clientobjresponse->location->address);
           $whd_model->clientcity = utf8_encode($clientobjresponse->location->city);
           $whd_model->clientlocationName = utf8_encode($clientobjresponse->location->locationName);
           $whd_model->clientpostalCode = utf8_encode($clientobjresponse->location->postalCode);
           $whd_model->clientstate = utf8_encode($clientobjresponse->location->state);
           $whd_model->clientroom = utf8_encode($clientobjresponse->room);
           $whd_model->clientcompanyName = utf8_encode($clientobjresponse->companyName);

           // Get location data
           $location_uri = ''.($whd_uri).'/ra/Locations/'.($objresponse->location->id).'?apiKey='.($api_key).'&qualifier=((deleted %3D null) or (deleted %3D 0) or (deleted %3D 1))';
           $location_response = \Httpful\Request::get($client_uri)->send();
           $locobjresponse = $location_response->body;

           // Process location data
           $whd_model->locaddress = utf8_encode($locobjresponse->address);
           $whd_model->loccity = utf8_encode($locobjresponse->city);
           $whd_model->loccountry = utf8_encode($locobjresponse->country);
           $whd_model->locdomainName = utf8_encode($locobjresponse->domainName);
           $whd_model->locfax = utf8_encode($locobjresponse->fax);
           $whd_model->loclocationName = utf8_encode($locobjresponse->locationName);
           $whd_model->locnote = utf8_encode($locobjresponse->note);
           $whd_model->locphone = utf8_encode($locobjresponse->phone);
           $whd_model->locphone2 = utf8_encode($locobjresponse->phone2);
           $whd_model->locpostalCode = utf8_encode($locobjresponse->postalCode);
           $whd_model->locstate = utf8_encode($locobjresponse->state);
           $whd_model->loccolor = utf8_encode($locobjresponse->color);
           $whd_model->locbusinessZone = utf8_encode($locobjresponse->businessZone);           
           
           // Save it! :D
           $whd_model->save();
           $error = 'WHD data processed';
           
           
           if (conf('whd_write'))
            {
           // Write stuff back to WHD
           // Call in other db tables
           require_once(conf('application_path').'/modules/machine/machine_model.php');
           require_once(conf('application_path').'/modules/warranty/warranty_model.php');
           require_once(conf('application_path').'/modules/reportdata/reportdata_model.php');
           //require_once(conf('application_path').'/modules/network/network_model.php');
           $machine = new Machine_model($whd_model->serial_number);
           $warranty = new Warranty_model($whd_model->serial_number);
           $reportdata = new Reportdata_model($whd_model->serial_number);
           //$network = new Network_model($whd_model->serial_number);
               
           // Build the JSON to send back
           $write_back = new StdClass();
           //$write_back->macAddress = $network->ethernet;
           $write_back->networkAddress = $reportdata->remote_ip;
           $write_back->networkName = $machine->hostname;
           $write_back->purchaseDate = $warranty->purchase_date."T00:00:01Z";
           $write_back->warrantyExpirationDate = $warranty->end_date."T00:00:01Z";
           if (conf('whd_write_status'))
           {
                $write_back->version = "Last push from MunkiReport on ".date('l jS \of F Y h:i:s A');
           }
               
           $whd_json = json_encode($write_back);
               
           // Send it to WHD
           $write_uri = ''.($whd_uri).'/ra/Assets/'.($objresponse->id).'?apiKey='.($api_key);
           $write_response = \Httpful\Request::put($write_uri)->sendsJson()->body($whd_json)->send();
           }
       }
    
    	return $error;
}