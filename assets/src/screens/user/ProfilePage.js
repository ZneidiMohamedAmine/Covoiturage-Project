import React, { useState, useEffect } from 'react';
import './styles.css';

const ProfilePage = ({ user }) => {
  const [postsCreated, setPostsCreated] = useState([]); // Example state for posts created
  const [postsJoined, setPostsJoined] = useState([]); // Example state for posts joined

  useEffect(() => {
    // Example: Fetch user's posts data from API
    fetchPostsCreated();
    fetchPostsJoined();
  }, []);

  const fetchPostsCreated = () => {
    // Example: Fetch posts created by the user from API (replace with your actual API fetch logic)
    setPostsCreated([
      { id: 1, from: 'City A', to: 'City B', departure: '2024-07-15', seatsAvailable: 3, price: 25 },
      { id: 2, from: 'City C', to: 'City D', departure: '2024-07-16', seatsAvailable: 2, price: 20 },
    ]);
  };

  const fetchPostsJoined = () => {
    // Example: Fetch posts joined by the user from API (replace with your actual API fetch logic)
    setPostsJoined([
      { id: 3, from: 'City E', to: 'City F', departure: '2024-07-17', seatsAvailable: 1, price: 15 },
    ]);
  };

  const handleLogout = (e) => {
    e.preventDefault();
    // Example: Perform logout logic (clear session, redirect, etc.)
  };

  return (
    <div className="profile-page">
      <div className="header">
        {user && (
          <form onSubmit={handleLogout}>
            <button type="submit">Logout</button>
          </form>
        )}
      </div>

      <div className="profile-header">
        {user && (
          <>
            <img src="profile-large.jpg" alt="Profile Picture" />
            <div className="profile-info">
              <h2>{user.name}</h2>
              <p><strong>Email:</strong> {user.email}</p>
              <p><strong>Phone:</strong> {user.phone}</p>
              <p><strong>Bio:</strong> {user.bio}</p>
            </div>
          </>
        )}
      </div>

      <div className="profile-content">
        <h3>Posts Created</h3>
        {postsCreated.map(post => (
          <div key={post.id} className="post">
            <p><strong>From:</strong> {post.from}</p>
            <p><strong>To:</strong> {post.to}</p>
            <p><strong>Departure:</strong> {post.departure}</p>
            <p><strong>Seats Available:</strong> {post.seatsAvailable}</p>
            <p><strong>Price:</strong> €{post.price}</p>
          </div>
        ))}

        <h3>Posts Joined</h3>
        {postsJoined.map(post => (
          <div key={post.id} className="post">
            <p><strong>From:</strong> {post.from}</p>
            <p><strong>To:</strong> {post.to}</p>
            <p><strong>Departure:</strong> {post.departure}</p>
            <p><strong>Seats Available:</strong> {post.seatsAvailable}</p>
            <p><strong>Price:</strong> €{post.price}</p>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ProfilePage;
