import OrgChart from 'orgchart.js';
import 'orgchart.js/dist/css/jquery.orgchart.min.css';

window.initOrgChart = function(data) {
    const container = document.getElementById('orgchart-container');
    
    if (!container || !data || data.length === 0) {
        console.error('No container or data found for org chart');
        return;
    }

    // Transform data to OrgChart.js format
    function transformData(users) {
        if (!users || users.length === 0) return null;
        
        const rootUser = users[0];
        
        return {
            id: rootUser.id,
            name: rootUser.name,
            title: rootUser.title,
            className: rootUser.is_active ? 'active-user' : 'inactive-user',
            children: rootUser.subordinates && rootUser.subordinates.length > 0 
                ? rootUser.subordinates.map(sub => transformData([sub]))
                : undefined
        };
    }

    const chartData = transformData(data);
    
    if (!chartData) {
        container.innerHTML = '<div class="text-center py-12 text-gray-500">No organization data available</div>';
        return;
    }

    // Initialize OrgChart
    const chart = new OrgChart({
        chartContainer: container,
        data: chartData,
        nodeContent: 'title',
        pan: true,
        zoom: true,
        direction: 't2b', // top to bottom
        verticalLevel: 4,
        depth: 999,
        nodeTemplate: function(data) {
            return `
                <div class="org-node">
                    <div class="org-node-avatar">
                        ${data.name.substring(0, 2).toUpperCase()}
                    </div>
                    <div class="org-node-info">
                        <div class="org-node-name">${data.name}</div>
                        <div class="org-node-title">${data.title}</div>
                    </div>
                </div>
            `;
        }
    });

    return chart;
};
