import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';

const JoinedTrip = () => {
  const [postsCreated, setPostsCreated] = useState([]);
  const [postsJoined, setPostsJoined] = useState([]);
  const [loading, setLoading] = useState(true);
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
    // Implement logout functionality
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

  const handleModifier = async (trajetid) => {
    try {
      const response = await fetch(`/api/trajet/modifier`, {
        method: 'UPDATE',
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
              <button className="btn btn-warning" onClick={() => handleModifier(post.trajetid)}>Modifier</button>
              <button className="btn btn-danger" onClick={() => handleAnnuler(post.trajetid)}>
                Annuler
              </button>
            </div>
          ))
        ) : (
          <p>No created trips found.</p>
        )}
      </div>
    </div>
  );
};

export default JoinedTrip;
