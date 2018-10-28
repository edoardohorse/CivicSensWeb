    <script src="../js/table.js"></script>
    <script src="../js/utils.js"></script>
    <script src="../js/details.js"></script>
    <script src="../js/admin.js"></script>
    <script src="../js/team.js"></script>
    <script src="../js/report.js"></script>
    <script src="../js/hub.js"></script>
    <script src="../js/vex.combined.min.js"></script>
    <script src="../js/script.js"></script>
    <script>
        window.managerDet = new ManagerDetails(document.body)
        window.manager = new ManagerReport(substitute(URL_FETCH_REPORTS_BY_TEAM,['Enel2']))
        window.team = new Team('Enel2')
        team.fetchInfo()

        showReportsEnte()
        
    </script>
    <script src="../js/actionsTeam.js"></script>
    
</html>