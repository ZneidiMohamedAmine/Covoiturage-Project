import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';

const JoinedTrip = () => {
  const [postsCreated, setPostsCreated] = useState([]);
  const [postsJoined, setPostsJoined] = useState([]);
  const [loading, setLoading] = useState(true);
  const [userInfo, setUserInfo] = useState({});
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState({
    stars: '',
    description: ''
  });
  const [editCommentIndex, setEditCommentIndex] = useState(null); // Track the index of the comment being edited
  const [editedComment, setEditedComment] = useState({
    stars: '',
    description: '',
    commentId: ''
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
          body: JSON.stringify({ idprofile: localStorage.getItem('idprofile') })
        });

        if (!response.ok) {
          throw new Error('Failed to fetch profile data');
        }

        const data = await response.json();
        setPostsCreated(data.tripcreated || []);
        setPostsJoined(data.tripjoined || []);
        setUserInfo({
          currentUser: data.userinfo.currentuser,
          authUser: data.userinfo.authuser,
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
    return () => {
      localStorage.removeItem('idprofile');
    };
  }, []);

  const handleHome = () => {
    navigate('/');
  };

  const handleLogout = () => {
    localStorage.removeItem('jwtToken');
    navigate('/logout');
  };

  const handleCommentChange = (event) => {
    const { name, value } = event.target;
    setNewComment((prev) => ({
      ...prev,
      [name]: value
    }));
  };

  const handlePostComment = async () => {
    try {
      const response = await fetch('/api/comment', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ...newComment, profileId: userInfo.currentUser })
      });

      if (!response.ok) {
        throw new Error('Failed to post comment');
      }

      const data = await response.json();
      setComments((prev) => [...prev, data.comment]);
    } catch (error) {
      console.error('Error posting comment:', error);
    }
  };

  const handleEditComment = (index) => {
    setEditCommentIndex(index);
    setEditedComment({
      stars: comments[index].Stars,
      description: comments[index].Description,
      commentId: comments[index].commentId
    });
  };

  const handleCancelEdit = () => {
    setEditCommentIndex(null);
    setEditedComment({
      stars: '',
      description: '',
      commentId: '',
    });
  };

  const handleSaveComment = async (commentId, index) => {
    try {
      const response = await fetch('/api/comment/modifier', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ editedComment })
      });

      if (!response.ok) {
        throw new Error('Failed to modify comment');
      }

      const data = await response.json();
      setComments((prev) =>
        prev.map((comment, i) =>
          i === index
            ? { ...comment, Stars: data.stars, Description: data.description }
            : comment
        )
      );

      handleCancelEdit();
    } catch (error) {
      console.error('Error modifying comment:', error);
    }
  };

  const handleEditChange = (event) => {
    const { name, value } = event.target;
    setEditedComment((prev) => ({
      ...prev,
      [name]: value
    }));
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
        {userInfo.currentUser !== userInfo.authUser && (
          <div className="new-comment mb-4">
            <h4>Leave a Comment</h4>
            <div className="form-group">
              <label htmlFor="stars">Stars:</label>
              <input
                type="number"
                className="form-control"
                id="stars"
                name="stars"
                value={newComment.stars}
                onChange={handleCommentChange}
                required
              />
            </div>
            <div className="form-group">
              <label htmlFor="description">Description:</label>
              <textarea
                className="form-control"
                id="description"
                name="description"
                value={newComment.description}
                onChange={handleCommentChange}
                required
              ></textarea>
            </div>
            <button className="btn btn-primary" onClick={handlePostComment}>Post Comment</button>
          </div>
        )}
        {comments.length > 0 ? (
          comments.map((comment, index) => (
            <div className="comment" key={index}>
              {editCommentIndex === index ? (
                <>
                  <div className="form-group">
                    <label htmlFor={`edit-stars-${index}`}>Stars:</label>
                    <input
                      type="number"
                      className="form-control"
                      id={`edit-stars-${index}`}
                      name="stars"
                      value={editedComment.stars}
                      onChange={handleEditChange}
                      required
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor={`edit-description-${index}`}>Description:</label>
                    <textarea
                      className="form-control"
                      id={`edit-description-${index}`}
                      name="description"
                      value={editedComment.description}
                      onChange={handleEditChange}
                      required
                    ></textarea>
                  </div>
                  <button className="btn btn-success" onClick={() => handleSaveComment(comment.commenterId, index)}>Confirm</button>
                  <button className="btn btn-secondary" onClick={handleCancelEdit}>Cancel</button>
                </>
              ) : (
                <>
                  <p><strong>Stars:</strong> {comment.Stars || 'N/A'}</p>
                  <p><strong>Description:</strong> {comment.Description || 'N/A'}</p>
                  <p><strong>Commenter Name:</strong> {comment.commentername}</p>
                  <p><strong>Commenter Last Name:</strong> {comment.commenterLastname}</p>
                  <p><strong>Commenter Id:</strong> {comment.commenterId}</p>
                  {userInfo.authUser === comment.commenterId && (
                    <>
                      <button className="btn btn-warning" onClick={() => handleEditComment(index)}>Modifier</button>
                      <button className="btn btn-danger">Supprimer</button>
                    </>
                  )}
                  <button className="btn btn-secondary">Rapporter</button>
                </>
              )}
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
