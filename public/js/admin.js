
const sampleQuestions = [
    { title: "Sample Question 1", content: "Lorem ipsum dolor sit amet..." },
    { title: "Sample Question 2", content: "Consectetur adipiscing elit..." },
    
];


function displayQuestions() {
    const questionList = document.getElementById("questionList");

    
    questionList.innerHTML = "";

   
    sampleQuestions.forEach((question, index) => {
        const questionCard = createQuestionCard(question, index);
        questionList.appendChild(questionCard);
    });
}


function createQuestionCard(question, index) {
    const questionCard = document.createElement("div");
    questionCard.classList.add("question-card");
    questionCard.id = `question-${index}`;

    questionCard.innerHTML = `
        <h3>${question.title}</h3>
        <p>${question.content}</p>
        <button onclick="approveQuestion(${index})">Approve</button>
        <button onclick="removeQuestion(${index})">Remove</button>
    `;

    return questionCard;
}


function approveQuestion(index) {

    console.log(`Question ${index + 1} approved.`);
}


function removeQuestion(index) {

    sampleQuestions.splice(index, 1);
    displayQuestions(); 
    console.log(`Question ${index + 1} removed.`);
}


document.addEventListener("DOMContentLoaded", displayQuestions);
