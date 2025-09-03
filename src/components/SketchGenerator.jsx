import React, { useState } from 'react';

function SketchGenerator() {
  // Add CSS keyframes for spinner animation
  React.useEffect(() => {
    const style = document.createElement('style');
    style.textContent = `
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
    return () => document.head.removeChild(style);
  }, []);
  const [prompt, setPrompt] = useState('');
  const [imageUrl, setImageUrl] = useState(null);
  const [loading, setLoading] = useState(false);

  const generateSketch = async () => {
    if (!prompt) return;
    setLoading(true);
    setImageUrl(null);

    try {
      const response = await fetch('http://localhost:5000/generate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt }),
      });

      if (!response.ok) {
        throw new Error('Failed to generate sketch');
      }

      // Backend returns raw image (PNG), so convert to blob
      const blob = await response.blob();
      setImageUrl(URL.createObjectURL(blob));
    } catch (err) {
      console.error(err);
      alert('Error: Could not generate sketch. Make sure the AI backend is running on port 5000.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="sketch-generator" style={{ 
      padding: '2rem', 

      background: 'linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%)'
    }}>
      <div className="hero-section2" style={{ 
        textAlign: 'center', 
        marginBottom: '3rem',
        padding: '2rem',
        backgroundColor: '#1976d2',
        borderRadius: '15px',
        color: 'white',
        boxShadow: '0 4px 15px rgba(25, 118, 210, 0.3)'
      }}>
        <h1 style={{ fontSize: '2.5rem', marginBottom: '1rem', color: 'white' }}>AI Craft Image Generator</h1>
        <p style={{ fontSize: '1.2rem', color: '#e3f2fd', marginBottom: '0' }}>
          Transform your craft ideas into visual sketches using AI technology
        </p>
      </div>

      <div className="generator-section" style={{ 
        backgroundColor: 'white', 
        padding: '2rem', 
        borderRadius: '15px',
        boxShadow: '0 4px 20px rgba(25, 118, 210, 0.15)',
        border: '2px solid #e3f2fd'
      }}>
        <div className="input-section" style={{ marginBottom: '2rem' }}>
          <label style={{ 
            display: 'block', 
            marginBottom: '0.5rem', 
            fontWeight: 'bold',
            color: '#1976d2',
            fontSize: '18px'
          }}>
            Describe your craft design:
          </label>
          <textarea
            placeholder="e.g., A modern summer dress with floral patterns, short sleeves, and a flowing skirt..."
            value={prompt}
            onChange={(e) => setPrompt(e.target.value)}
            style={{ 
              width: '100%', 
              minHeight: '100px',
              padding: '15px', 
              border: '2px solid #2196f3',
              borderRadius: '12px',
              fontSize: '16px',
              fontWeight:'bold',
              color: 'black',
              resize: 'vertical',
              fontFamily: 'inherit',
              backgroundColor: 'white',
              transition: 'border-color 0.3s, box-shadow 0.3s',
              outline: 'none'
            }}
            onFocus={(e) => {
              e.target.style.borderColor = '#1976d2';
              e.target.style.boxShadow = '0 0 0 3px rgba(25, 118, 210, 0.1)';
            }}
            onBlur={(e) => {
              e.target.style.borderColor = '#2196f3';
              e.target.style.boxShadow = 'none';
            }}
          />
        </div>
        
        <button 
          onClick={generateSketch} 
          disabled={loading || !prompt.trim()}
          style={{
            backgroundColor: loading || !prompt.trim() ? '#bbdefb' : '#1976d2',
            color: 'white',
            border: 'none',
            padding: '15px 35px',
            borderRadius: '12px',
            fontSize: '18px',
            fontWeight: 'bold',
            cursor: loading || !prompt.trim() ? 'not-allowed' : 'pointer',
            transition: 'all 0.3s ease',
            boxShadow: loading || !prompt.trim() ? 'none' : '0 4px 15px rgba(25, 118, 210, 0.3)',
            transform: loading || !prompt.trim() ? 'none' : 'translateY(-2px)'
          }}
          onMouseEnter={(e) => {
            if (!loading && prompt.trim()) {
              e.target.style.backgroundColor = '#1565c0';
              e.target.style.transform = 'translateY(-3px)';
              e.target.style.boxShadow = '0 6px 20px rgba(25, 118, 210, 0.4)';
            }
          }}
          onMouseLeave={(e) => {
            if (!loading && prompt.trim()) {
              e.target.style.backgroundColor = '#1976d2';
              e.target.style.transform = 'translateY(-2px)';
              e.target.style.boxShadow = '0 4px 15px rgba(25, 118, 210, 0.3)';
            }
          }}
        >
          {loading ? 'Generating Sketch...' : 'Generate Sketch'}
        </button>
      </div>

      {imageUrl && (
        <div className="result-section" style={{ 
          marginTop: '3rem',
          textAlign: 'center',
          backgroundColor: 'white',
          padding: '2rem',
          borderRadius: '15px',
          boxShadow: '0 4px 20px rgba(25, 118, 210, 0.15)',
          border: '2px solid #e3f2fd'
        }}>
          <h3 style={{ marginBottom: '1.5rem', color: '#1976d2', fontSize: '24px', fontWeight: 'bold' }}>Generated Sketch:</h3>
          <img
            src={imageUrl}
            alt="Generated Clothing Sketch"
            style={{ 
              maxWidth: '100%', 
              maxHeight: '500px',
              border: '3px solid #2196f3',
              borderRadius: '12px',
              boxShadow: '0 8px 25px rgba(25, 118, 210, 0.2)'
            }}
          />
          <div style={{ marginTop: '1.5rem' }}>
            <p style={{ color: '#1976d2', fontSize: '16px', fontWeight: '500' }}>
              Right-click the image to save it to your device
            </p>
          </div>
        </div>
      )}

      {loading && (
        <div style={{
          marginTop: '2rem',
          textAlign: 'center',
          padding: '2rem',
          backgroundColor: 'white',
          borderRadius: '15px',
          border: '2px solid #e3f2fd',
          boxShadow: '0 4px 20px rgba(25, 118, 210, 0.15)'
        }}>
          <div style={{
            width: '50px',
            height: '50px',
            border: '4px solid #e3f2fd',
            borderTop: '4px solid #1976d2',
            borderRadius: '50%',
            animation: 'spin 1s linear infinite',
            margin: '0 auto 1rem'
          }}></div>
          <p style={{ color: '#1976d2', fontSize: '18px', fontWeight: '500' }}>
            Creating your craft sketch...
          </p>
        </div>
      )}

      {/* <div className="info-section" style={{ 
        marginTop: '3rem',
        padding: '2rem',
        backgroundColor: '#e3f2fd',
        borderRadius: '15px',
        border: '2px solid #bbdefb',
        boxShadow: '0 2px 10px rgba(25, 118, 210, 0.1)'
      }}>
        <h4 style={{ color: '#1976d2', marginBottom: '1rem', fontSize: '20px', fontWeight: 'bold' }}>💡 Tips for better results:</h4>
        <ul style={{ color: '#1565c0', lineHeight: '1.8', fontSize: '16px' }}>
          <li>Be specific about clothing type (dress, shirt, pants, etc.)</li>
          <li>Include details about style, patterns, and colors</li>
          <li>Mention specific features like sleeves, neckline, or length</li>
          <li>Add context like occasion or season if relevant</li>
        </ul>
      </div> */}
    </div>
  );
}

export default SketchGenerator;