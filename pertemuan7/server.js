import express from "express";
import cors from "cors";

const app = express();
app.use(cors());
app.use(express.json());

app.post("/api/contact", (req, res) => {
  const { name, email, message } = req.body;
  if (!name || !email || !message) {
    return res.status(400).json({ error: "All fields are required." });
  }
  console.log("📩 Message received:", req.body);
  res.json({ message: `Thanks ${name}! We'll reply to ${email}.` });
});

app.listen(3000, () => console.log("Server running on http://localhost:3000"));
