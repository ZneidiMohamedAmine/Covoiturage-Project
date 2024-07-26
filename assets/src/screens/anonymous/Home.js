import React, { useState, useEffect } from 'react';
//import './styles.css'; // Make sure to import the CSS for styles

const Home = () => {
    const [trajets, setTrajets] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [formData, setFormData] = useState({
        date: '',
        time: '',
        villedebut: '',
        villedestination: '',
        ruedebut: '',
        ruedestination: '',
        seatsoccupied: '',
        seatsavailable: '',
        price: ''
    });

    useEffect(() => {
        const fetchTrajets = async () => {
            try {
                const response = await fetch('/api/'); // Adjust the endpoint as needed
                if (!response.ok) throw new Error('Failed to fetch trajets');
                const data = await response.json();
                setTrajets(data);
            } catch (error) {
                setError(error.message);
            } finally {
                setLoading(false);
            }
        };

        fetchTrajets();
    }, []);

    const handleInputChange = (event) => {
        const { name, value } = event.target;
        setFormData(prevData => ({ ...prevData, [name]: value }));
    };

    const handleFormSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await fetch('/api/trajet', { // Adjust the endpoint as needed
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            if (!response.ok) throw new Error('Failed to create trajet');
            // Clear form data after successful submission or handle success state here
            setFormData({
                date: '',
                time: '',
                villedebut: '',
                villedestination: '',
                ruedebut: '',
                ruedestination: '',
                seatsoccupied: '',
                seatsavailable: '',
                price: ''
            });
        } catch (error) {
            setError(error.message);
        }
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div>
            <div className="header">
                {true /* Replace with actual authentication check */}
                    <form action="/api/logout" method="POST">
                        <button type="submit" className="btn btn-danger">Logout</button>
                    </form>
                    <form action="/control-panel" method="POST">
                        <button type="submit" className="btn btn-success">Control Panel</button>
                    </form>
                {!true /* Replace with actual authentication check */}
                    <form action="/login" method="POST">
                        <button type="submit" className="btn btn-primary">Login</button>
                    </form>
                    <form action="/register" method="POST">
                        <button type="submit" className="btn btn-success">Register</button>
                    </form>
            </div>

            <div className="search-container">
                <form method="get" className="form-inline">
                    <input type="text" name="query" className="form-control mr-sm-2" placeholder="Search..." />
                    <button type="submit" className="btn btn-outline-primary">Search</button>
                </form>
            </div>

            <div className="create-trajet-container">
                <h2>Create New Trajet</h2>
                <form id="trajet-form" onSubmit={handleFormSubmit}>
                    <label htmlFor="date">Date:</label>
                    <input type="date" id="date" name="date" value={formData.date} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="time">Time:</label>
                    <input type="time" id="time" name="time" value={formData.time} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="villedebut">Starting City:</label>
                    <input type="text" id="villedebut" name="villedebut" value={formData.villedebut} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="villedestination">Destination City:</label>
                    <input type="text" id="villedestination" name="villedestination" value={formData.villedestination} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="ruedebut">Starting Street:</label>
                    <input type="text" id="ruedebut" name="ruedebut" value={formData.ruedebut} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="ruedestination">Destination Street:</label>
                    <input type="text" id="ruedestination" name="ruedestination" value={formData.ruedestination} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="seatsoccupied">Seats Occupied:</label>
                    <input type="number" id="seatsoccupied" name="seatsoccupied" value={formData.seatsoccupied} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="seatsavailable">Seats Available:</label>
                    <input type="number" id="seatsavailable" name="seatsavailable" value={formData.seatsavailable} onChange={handleInputChange} required /><br /><br />

                    <label htmlFor="price">Price:</label>
                    <input type="number" id="price" name="price" value={formData.price} onChange={handleInputChange} step="0.01" required /><br /><br />

                    <button type="submit" className="btn btn-primary">Create Trajet</button>
                </form>
            </div>

            <div id="post">
                <h2>Available Trips</h2>
                <ul>
                    {trajets.length > 0 ? (
                        trajets.map((trip, index) => (
                            <li key={index}>
                                <strong>Date:</strong> {trip.date} <br />
                                <strong>Time:</strong> {trip.time} <br />
                                <strong>Seats Available:</strong> {trip.seatsAvailable} <br />
                                <strong>Seats Occupied:</strong> {trip.seatsOccupied} <br />
                                <strong>Price:</strong> {trip.price} <br />
                                <strong>Debut Ville:</strong> {trip.debutVille} <br />
                                <strong>Debut Rue:</strong> {trip.debutRue} <br />
                                <strong>Destination Ville:</strong> {trip.destinationVille} <br />
                                <strong>Destination Rue:</strong> {trip.destinationRue} <br />
                                <form action={`/reserve/${trip.id}`} method="POST">
                                    <button type="submit" className="btn btn-success">Reserve</button>
                                </form>
                            </li>
                        ))
                    ) : (
                        <li>No trips available.</li>
                    )}
                </ul>
            </div>
        </div>
    );
};

export default Home;
