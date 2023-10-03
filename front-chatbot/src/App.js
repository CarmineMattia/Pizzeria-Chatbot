import React, { useState, useEffect, useRef } from "react";
import axios from "axios";
import "./App.css";

function App() {
  const [messages, setMessages] = useState([]);
  const [input, setInput] = useState("");
  const [errorMessage, setErrorMessage] = useState(null); // New state for error message
  const messagesEndRef = useRef(null);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [messages]);

  const handleSend = () => {
    if (input) {
      setMessages([...messages, { text: input, sender: "user" }]);

      axios
        .post("http://localhost:8000/api/chatbot/respond", { message: input })
        .then((response) => {
          if (response.data.error) {
            setErrorMessage(response.data.error); // Set the error message
          } else {
            setMessages((prevMessages) => [
              ...prevMessages,
              { text: response.data.response.trim(), sender: "bot" },
            ]);
          }
        })
        .catch((error) => {
          console.error("Error sending message:", error);
          setErrorMessage(
            "🚫 Oops! To place an order, type in the format: /ordine [pizza_name] [quantity]. For example: /ordine Margherita 2"
          ); // Handle unexpected errors
        });

      setInput("");
    }
  };
  const handleKeyPress = (event) => {
    if (event.key === "Enter") {
      handleSend();
    }
  };

  return (
    <div className="App">
      <h1>Ambrosia Chatbot 🍕</h1>
      <p>Welcome to our digital assistant! Here's how you can interact:</p>
      <ul>
        <li className="li-text-color">
          To see the menu, type <code>/menu</code>
        </li>
        <li className="li-text-color">
          To place an order, type <code>/ordine</code>
        </li>
        <li className="li-text-color">
          or just type <code>Hello</code>
        </li>
      </ul>
      <p>
        📢 This is an experimental version. Responses might vary. Enjoy your
        experience! 🙌
      </p>

      <div className="chat">
        <div className="chat-header">
          <h2>Matteo-Chatbot 🤖🍕</h2>
        </div>
        <div className="chat-content">
          {messages.map((message, index) => (
            <div key={index} className={`chat-message ${message.sender}`}>
              <p>{message.text}</p>
            </div>
          ))}
          <div ref={messagesEndRef}></div>
        </div>
        {errorMessage && <div className="error-message">{errorMessage}</div>}{" "}
        {/* Display the error message if it exists */}
        <div className="chat-controls">
          <input
            type="text"
            className="chat-input"
            value={input}
            onChange={(e) => setInput(e.target.value)}
            placeholder="Type message..."
            onKeyPress={handleKeyPress}
          />
          <button className="chat-send" onClick={handleSend}>
            Send
          </button>
        </div>
      </div>
    </div>
  );
}

export default App;

// ****************************
// ******************************
// import React, { useState, useEffect, useRef } from "react";
// import axios from "axios";
// import "./App.css";

// function App() {
//   const [messages, setMessages] = useState([]);
//   const [input, setInput] = useState("");
//   const messagesEndRef = useRef(null);

//   useEffect(() => {
//     messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
//   }, [messages]);

//   const handleSend = () => {
//     if (input) {
//       setMessages([...messages, { text: input, sender: "user" }]);

//       axios
//         .post("http://localhost:8000/api/chatbot/respond", { message: input })
//         .then((response) => {
//           setMessages((prevMessages) => [
//             ...prevMessages,
//             { text: response.data.response.trim(), sender: "bot" },
//           ]);
//         })
//         .catch((error) => {
//           console.error("Error sending message:", error);
//         });

//       setInput("");
//     }
//   };

//   return (
//     <div className="App">
//       <h1>Ambrosia Chatbot 🍕</h1>
//       <p>Welcome to our digital assistant! Here's how you can interact:</p>
//       <ul>
//         <li className="li-text-color">
//           To see the menu, type <code>/menu</code>
//         </li>
//         <li className="li-text-color">
//           To place an order, type <code>/ordine</code>
//         </li>
//       </ul>
//       <p>
//         📢 This is an experimental version. Responses might vary. Enjoy your
//         experience! 🙌
//       </p>

//       <div className="chat">
//         <div className="chat-header">
//           <h2>Matteo-Chatbot</h2>
//         </div>
//         <div className="chat-content">
//           {messages.map((message, index) => (
//             <div key={index} className={`chat-message ${message.sender}`}>
//               <p>{message.text}</p>
//             </div>
//           ))}
//           <div ref={messagesEndRef}></div>
//         </div>
//         <div className="chat-controls">
//           <input
//             type="text"
//             className="chat-input"
//             value={input}
//             onChange={(e) => setInput(e.target.value)}
//             placeholder="Type message..."
//           />
//           <button className="chat-send" onClick={handleSend}>
//             Send
//           </button>
//         </div>
//       </div>
//     </div>
//   );
// }

// export default App;
