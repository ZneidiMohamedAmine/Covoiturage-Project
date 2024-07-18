// reportWebVitals.js

const reportWebVitals = (onPerfEntry) => {
    if (onPerfEntry && onPerfEntry instanceof Function) {
      window.performance.getEntriesByType('mark').forEach((entry) => {
        if (entry.name === 'first-paint') {
          // Log first paint to Google Analytics or any other analytics service
          console.log('First Paint:', entry.startTime);
        }
        // You can add more conditions to log other metrics like 'first-contentful-paint', 'largest-contentful-paint', etc.
      });
    }
  };
  
  export default reportWebVitals;
  