import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Modal, Button, Form } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

const JoinedTrip = () => {
  const [postsCreated, setPostsCreated] = useState([]);
  const [postsJoined, setPostsJoined] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [currentTrajetId, setCurrentTrajetId] = useState(null);
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
  const navigate = useNavigate();

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const response = await fetch('/api/profile', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
        });

        if (!response.ok) {
          throw new Error('Failed to fetch profile data');
        }

        const data = await response.json();
        setPostsCreated(data.tripcurrentcreated || []);
        setPostsJoined(data.tripcurrentjoined || []);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching profile data:', error);
      }
    };

    fetchProfile();
  }, []);

  const handleHome = () => {
    navigate('/');
  };

  const handleLogout = () => {
    localStorage.removeItem('jwtToken'); // Remove the token from localStorage
    navigate('/logout');
  };

  const handleAnnuler = async (trajetid) => {
    try {
      const response = await fetch(`/api/trajet/supprimer`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ trajetid }), // Ensuring trajetid is sent in correct case
      });

      if (!response.ok) {
        throw new Error('Failed to delete trip');
      }

      // Optionally, update the state to remove the deleted trip from the UI
      setPostsCreated((prevPosts) =>
        prevPosts.filter((post) => post.trajetid !== trajetid)
      );
      setPostsJoined((prevPosts) =>
        prevPosts.filter((post) => post.trajetid !== trajetid)
      );
    } catch (error) {
      console.error('Error deleting trip:', error);
    }
  };

  const handleShowModal = (trajetid) => {
    setCurrentTrajetId(trajetid);
    setShowModal(true);
  };

  const handleCloseModal = () => {
    setShowModal(false);
    setFormData({
      date: '',
      time: '',
      villedebut: '',
      villedestination: '',
      ruedebut: '',
      ruedestination: '',
      seatsoccupied: '',
      price: ''
    });
  };

  const handleFormChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleModifier = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`/api/trajet/modifier`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ...formData, trajetid: currentTrajetId }),
      });

      if (!response.ok) {
        throw new Error('Failed to modify trip');
      }

      // Optionally, update the state to reflect the modified trip in the UI
      setPostsCreated((prevPosts) =>
        prevPosts.map((post) =>
          post.trajetid === currentTrajetId ? { ...post, ...formData } : post
        )
      );
      setPostsJoined((prevPosts) =>
        prevPosts.map((post) =>
          post.trajetid === currentTrajetId ? { ...post, ...formData } : post
        )
      );
      handleCloseModal();
    } catch (error) {
      console.error('Error modifying trip:', error);
    }
  };

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="joined-trip-page container">
      <header className="d-flex justify-content-between align-items-center my-3">
        <button className="btn btn-primary" onClick={handleHome}>
          Home
        </button>
        <form action="/logout" method="POST">
          <button type="submit" className="btn btn-danger" onClick={handleLogout}>
            Logout
          </button>
        </form>
      </header>
      <div className="joined-trips">
        <h3>Joined Trips</h3>
        {postsJoined.length > 0 ? (
          postsJoined.map((post, index) => (
            <div className="post" key={index}>
              <p><strong>Date:</strong> {post.date}</p>
              <p><strong>Time:</strong> {post.time}</p>
              <p><strong>From:</strong> {post.debutVille}, {post.debutRue}</p>
              <p><strong>To:</strong> {post.destinationVille}, {post.destinationRue}</p>
              <p><strong>Seats Available:</strong> {post.seatsAvailable}</p>
              <p><strong>Seats Occupied:</strong> {post.seatsOccupied}</p>
              <p><strong>Price:</strong> €{post.price}</p>
              
              <button className="btn btn-danger" onClick={() => handleAnnuler(post.trajetid)}>Annuler</button>
            </div>
          ))
        ) : (
          <p>No joined trips found.</p>
        )}
      </div>
      <div className="created-trips mt-4">
        <h3>Created Trips</h3>
        {postsCreated.length > 0 ? (
          postsCreated.map((post, index) => (
            <div className="post" key={index}>
              <p><strong>Date:</strong> {post.date}</p>
              <p><strong>Time:</strong> {post.time}</p>
              <p><strong>From:</strong> {post.debutVille}, {post.debutRue}</p>
              <p><strong>To:</strong> {post.destinationVille}, {post.destinationRue}</p>
              <p><strong>Seats Available:</strong> {post.seatsAvailable}</p>
              <p><strong>Seats Occupied:</strong> {post.seatsOccupied}</p>
              <p><strong>Price:</strong> €{post.price}</p>
              <button className="btn btn-warning" onClick={() => handleShowModal(post.trajetid)}>Modifier</button>
              <button className="btn btn-danger" onClick={() => handleAnnuler(post.trajetid)}>
                Annuler
              </button>
            </div>
          ))
        ) : (
          <p>No created trips found.</p>
        )}
      </div>

      <Modal show={showModal} onHide={handleCloseModal}>
        <Modal.Header closeButton>
          <Modal.Title>Modify Trip</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleModifier}>
            <Form.Group controlId="date">
              <Form.Label>Date</Form.Label>
              <Form.Control
                type="date"
                name="date"
                value={formData.date}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Form.Group controlId="time">
              <Form.Label>Time</Form.Label>
              <Form.Control
                type="time"
                name="time"
                value={formData.time}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Form.Group controlId="villedebut">
              <Form.Label>From City</Form.Label>
              <Form.Control
                type="text"
                name="villedebut"
                value={formData.villedebut}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Form.Group controlId="villedestination">
              <Form.Label>To City</Form.Label>
              <Form.Control
                type="text"
                name="villedestination"
                value={formData.villedestination}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Form.Group controlId="ruedebut">
              <Form.Label>From Street</Form.Label>
              <Form.Control
                type="text"
                name="ruedebut"
                value={formData.ruedebut}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Form.Group controlId="ruedestination">
              <Form.Label>To Street</Form.Label>
              <Form.Control
                type="text"
                name="ruedestination"
                value={formData.ruedestination}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Form.Group controlId="seatsoccupied">
              <Form.Label>Seats Occupied</Form.Label>
              <Form.Control
                type="number"
                name="seatsoccupied"
                value={formData.seatsoccupied}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Form.Group controlId="price">
              <Form.Label>Price (€)</Form.Label>
              <Form.Control
                type="number"
                name="price"
                value={formData.price}
                onChange={handleFormChange}
                required
              />
            </Form.Group>
            <Button variant="primary" type="submit">
              Modifier
            </Button>
          </Form>
        </Modal.Body>
      </Modal>
    </div>
  );
};

export default JoinedTrip;
