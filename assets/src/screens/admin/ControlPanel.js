import React from 'react';

const ControlPanel = () => {
    return (
        <div className="control-panel">
            <h1>Control Panel</h1>
            <div className="buttons">
                <form action="/logout" method="POST">
                    <button type="submit" className="btn btn-danger">Logout</button>
                </form>
                <form action="/dashboard" method="GET">
                    <button type="submit" className="btn btn-success">Dashboard</button>
                </form>
            </div>
        </div>
    );
};

export default ControlPanel;
