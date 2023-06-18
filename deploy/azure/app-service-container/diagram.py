#!/usr/bin/env python3

from diagrams import Cluster, Diagram
from diagrams.azure.general import Resourcegroups
from diagrams.azure.storage import BlobStorage
from diagrams.azure.web import AppServices, AppServicePlans
from diagrams.azure.database import DatabaseForMysqlServers

with Diagram("App Service Single-Container Public Deployment"):
    with Cluster('MunkiReportPHP-rg'):

        asp = AppServicePlans('munkireport-asp (b1s)')
        app = AppServices('munkireport-app')
        storage = BlobStorage('munkireport')
        db = DatabaseForMysqlServers('munkireport-database')

        app - asp
        app >> db
        app >> storage

    rg = Resourcegroups('MunkiReportPHP-rg')
