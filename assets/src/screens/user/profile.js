import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';

const JoinedTrip = () => {
  const [postsCreated, setPostsCreated] = useState([]);
  const [postsJoined, setPostsJoined] = useState([]);
  const [loading, setLoading] = useState(true);
  const [userInfo, setUserInfo] = useState({});
  const [comments, setComments] = useState([]);
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
        setPostsCreated(data.tripcreated || []);
        setPostsJoined(data.tripjoined || []);
        setUserInfo({
          firstName: data.userinfo.Firstname,
          lastName: data.userinfo.Lastname,
          gender: data.userinfo.Gender,
          driverLicense: data.userinfo.DriverLicense,
        });
        setComments(data.comments || []);
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

  const handleAnnuler = async (trajetId) => {
    try {
      const response = await fetch(`/api/trajet/supprimer`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ trajetid: trajetId }), // Ensuring trajetid is sent in correct case
      });

      if (!response.ok) {
        throw new Error('Failed to delete trip');
      }

      // Optionally, update the state to remove the deleted trip from the UI
      setPostsCreated((prevPosts) =>
        prevPosts.filter((post) => post.trajetId !== trajetId)
      );
      setPostsJoined((prevPosts) =>
        prevPosts.filter((post) => post.trajetId !== trajetId)
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
        <button className="btn btn-danger" onClick={handleLogout}>
          Logout
        </button>
      </header>

      <div className="user-info mb-4">
        <h3>User Information</h3>
        <p><strong>First Name:</strong> {userInfo.firstName}</p>
        <p><strong>Last Name:</strong> {userInfo.lastName}</p>
        <p><strong>Gender:</strong> {userInfo.gender}</p>
        <p><strong>Driver License:</strong> {userInfo.driverLicense ? 'Yes' : 'No'}</p>
      </div>

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
            </div>
          ))
        ) : (
          <p>No created trips found.</p>
        )}
      </div>

      <div className="comments mt-4">
        <h3>Comments</h3>
        {comments.length > 0 ? (
          comments.map((comment, index) => (
            <div className="comment" key={index}>
              <p><strong>Stars:</strong> {comment.Stars}</p>
              <p><strong>Description:</strong> {comment.Description}</p>
              <p><strong>Commenter Name:</strong> {comment.commentername}</p>
              <p><strong>Commenter Last Name:</strong> {comment.commenterLastname}</p>
            </div>
          ))
        ) : (
          <p>No comments found.</p>
        )}
      </div>
    </div>
  );
};

export default JoinedTrip;