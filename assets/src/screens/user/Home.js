import React, { useState, useEffect } from 'react';
import { Modal, Button, Form } from 'react-bootstrap';

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
    const [showModal, setShowModal] = useState(false);
    const isAuthenticated = !!localStorage.getItem('jwtToken'); // Check if the JWT token is present

    useEffect(() => {
        

        fetchTrajets();
    }, []);

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

            // Update the state with the new trajet
            fetchTrajets();
            setShowModal(false); // Close modal on success
        } catch (error) {
            setError(error.message);
        }
    };

    const handleReserve = async (trajetid) => {
        try {
            const response = await fetch('/api/reservation', { // Adjust the endpoint as needed
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ trajetid }) // Ensuring id is sent in correct case
            });

            if (!response.ok) throw new Error('Failed to reserve');

            // Optionally, update the state to remove the reserved trip from the UI
            setTrajets((prevTrajets) =>
                prevTrajets.filter((trip) => trip.trajetid !== trajetid)
            );
        } catch (error) {
            console.error('Error reserving trip:', error);
            setError(error.message);
        }
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div>
            <div className="header">
                {isAuthenticated ? (
                    <>
                        <form action="/logout" method="POST">
                            <button type="submit" className="btn btn-danger">Logout</button>
                        </form>
                        <form action="/profile" method="POST">
                            <button type="submit" className="btn btn-success">Profile</button>
                        </form>
                        <form action="/yourtrip" method="POST">
                            <button type="submit" className="btn btn-success">Current Trip</button>
                        </form>
                    </>
                ) : (
                    <>
                        <form action="/login" method="POST">
                            <button type="submit" className="btn btn-primary">Login</button>
                        </form>
                        <form action="/register" method="POST">
                            <button type="submit" className="btn btn-success">Register</button>
                        </form>
                    </>
                )}
            </div>

            <div className="search-container">
                <form method="get" className="form-inline">
                    <input type="text" name="query" className="form-control mr-sm-2" placeholder="Search..." />
                    <button type="submit" className="btn btn-outline-primary">Search</button>
                </form>
            </div>

            {isAuthenticated && (
                <div className="create-trajet-container">
                    <h2>Create New Trajet</h2>
                    <Button variant="primary" onClick={() => setShowModal(true)}>
                        Create New Trajet
                    </Button>

                    <Modal show={showModal} onHide={() => setShowModal(false)}>
                        <Modal.Header closeButton>
                            <Modal.Title>Create New Trajet</Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                            <Form id="trajet-form" onSubmit={handleFormSubmit}>
                                <Form.Group>
                                    <Form.Label>Date:</Form.Label>
                                    <Form.Control type="date" id="date" name="date" value={formData.date} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Time:</Form.Label>
                                    <Form.Control type="time" id="time" name="time" value={formData.time} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Starting City:</Form.Label>
                                    <Form.Control type="text" id="villedebut" name="villedebut" value={formData.villedebut} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Destination City:</Form.Label>
                                    <Form.Control type="text" id="villedestination" name="villedestination" value={formData.villedestination} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Starting Street:</Form.Label>
                                    <Form.Control type="text" id="ruedebut" name="ruedebut" value={formData.ruedebut} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Destination Street:</Form.Label>
                                    <Form.Control type="text" id="ruedestination" name="ruedestination" value={formData.ruedestination} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Seats Occupied:</Form.Label>
                                    <Form.Control type="number" id="seatsoccupied" name="seatsoccupied" value={formData.seatsoccupied} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Seats Available:</Form.Label>
                                    <Form.Control type="number" id="seatsavailable" name="seatsavailable" value={formData.seatsavailable} onChange={handleInputChange} required />
                                </Form.Group>

                                <Form.Group>
                                    <Form.Label>Price:</Form.Label>
                                    <Form.Control type="number" id="price" name="price" value={formData.price} onChange={handleInputChange} step="0.01" required />
                                </Form.Group>

                                <Button variant="primary" type="submit">
                                    Create Trajet
                                </Button>
                            </Form>
                        </Modal.Body>
                    </Modal>
                </div>
            )}

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
                                {isAuthenticated ? (
                                    <Button variant="success" onClick={() => handleReserve(trip.trajetid)}>Reserve</Button>
                                ) : (
                                    <Button variant="primary" onClick={() => window.location.href = '/login'}>Reserve</Button>
                                )}
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
