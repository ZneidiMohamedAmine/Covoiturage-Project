import React, { useEffect, useState } from 'react';

const Profile = () => {
  const [userProfile, setUserProfile] = useState(null);
  const [postsCreated, setPostsCreated] = useState([]);
  const [postsJoined, setPostsJoined] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const response = await fetch('/api/profile', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          }
        });

        if (!response.ok) {
          throw new Error('Failed to fetch profile data');
        }

        const data = await response.json();
        setUserProfile(data.userinfo);
        setPostsCreated(data.tripcreated);
        setPostsJoined(data.tripjoined);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching profile data:', error);
      }
    };

    fetchProfile();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="profile-page container">
      <div className="profile-header row">
        <div className="col-md-4">
          <img src="profile-large.jpg" alt="Profile Picture" className="img-fluid" />
        </div>
        <div className="profile-info col-md-8">
          <h2>{userProfile?.name}</h2>
          <p><strong>Email:</strong> {userProfile?.email}</p>
          <p><strong>Phone:</strong> {userProfile?.phone}</p>
          <p><strong>Bio:</strong> {userProfile?.bio}</p>
        </div>
      </div>
      <div className="profile-content">
        <h3>Posts Created</h3>
        {postsCreated.map((post, index) => (
          <div className="post" key={index}>
            <p><strong>From:</strong> {post.from}</p>
            <p><strong>To:</strong> {post.to}</p>
            <p><strong>Departure:</strong> {post.departure}</p>
            <p><strong>Seats Available:</strong> {post.seatsAvailable}</p>
            <p><strong>Price:</strong> €{post.price}</p>
          </div>
        ))}
        
        <h3>Posts Joined</h3>
        {postsJoined.map((post, index) => (
          <div className="post" key={index}>
            <p><strong>From:</strong> {post.from}</p>
            <p><strong>To:</strong> {post.to}</p>
            <p><strong>Departure:</strong> {post.departure}</p>
            <p><strong>Seats Available:</strong> {post.seatsAvailable}</p>
            <p><strong>Price:</strong> €{post.price}</p>
          </div>
        ))}
      </div>
      <div className="buttons mt-3">
        <form action="/logout" method="POST">
          <button type="submit" className="btn btn-danger">Logout</button>
        </form>
      </div>
    </div>
  );
};

export default Profile;
