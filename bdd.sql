-- Table des utilisateurs (users)
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Hachage du mot de passe (par exemple bcrypt)
    verification_code VARCHAR(6),    -- Code à 6 chiffres pour la vérification
    is_verified BOOLEAN DEFAULT FALSE,  -- Utilisateur vérifié ou non (initialisé à faux)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des quiz (quizzes)
CREATE TABLE quizzes (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,                 -- Référence à l'utilisateur qui a créé le quiz
    title VARCHAR(255) NOT NULL,          -- Titre du quiz
    description TEXT,                     -- Brève description du quiz
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE  -- Clé étrangère vers la table users
);

-- Table des questions (questions)
CREATE TABLE questions (
    id SERIAL PRIMARY KEY,
    quiz_id INT NOT NULL,          -- Lien avec le quiz correspondant
    question_text TEXT NOT NULL,   -- Texte de la question
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Table des réponses (answers)
CREATE TABLE answers (
    id SERIAL PRIMARY KEY,
    question_id INT NOT NULL,        -- Lien avec la question correspondante
    answer_text VARCHAR(255) NOT NULL, -- Texte de la réponse
    is_correct BOOLEAN DEFAULT FALSE,  -- Indique si la réponse est correcte
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Table des résultats (results)
CREATE TABLE results (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,            -- Lien avec l'utilisateur
    quiz_id INT NOT NULL,            -- Lien avec le quiz
    score INT NOT NULL,              -- Score obtenu par l'utilisateur
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de fin du quiz
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Table de suivi des réponses utilisateur (user_answers)
CREATE TABLE user_answers (
    id SERIAL PRIMARY KEY,
    result_id INT NOT NULL,          -- Lien avec le résultat (tentative)
    question_id INT NOT NULL,        -- Lien avec la question
    answer_id INT NOT NULL,          -- Lien avec la réponse choisie
    FOREIGN KEY (result_id) REFERENCES results(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    FOREIGN KEY (answer_id) REFERENCES answers(id) ON DELETE CASCADE
);
