<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MistralService
{
    private const MISTRAL_API_URL = 'https://api.mistral.ai/v1/chat/completions';
    
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $mistralApiKey
    ) {}
    
    /**
     * Génère un QCM de 5 questions Vrai/Faux à partir d'un paragraphe
     */
    public function generateQcm(string $paragraph): array
    {
        $prompt = <<<PROMPT
À partir du paragraphe suivant, génère exactement 5 questions de type Vrai/Faux.
Réponds UNIQUEMENT avec un JSON valide sans texte supplémentaire.

Paragraphe:
{$paragraph}

Format attendu (JSON uniquement):
{
  "questions": [
    {
      "question": "texte de la question",
      "answer": true
    }
  ]
}

Règles:
- Exactement 5 questions
- Chaque question doit être claire et précise
- Les réponses doivent être true ou false (boolean)
- Mélange de questions vraies et fausses
- JSON valide uniquement
PROMPT;

        try {
            $response = $this->httpClient->request('POST', self::MISTRAL_API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->mistralApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'mistral-small-latest',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                ],
            ]);

            $data = $response->toArray();
            $content = $data['choices'][0]['message']['content'] ?? '';
            
            // Nettoyer le contenu (enlever les backticks markdown si présents)
            $content = preg_replace('/```json\s*|\s*```/', '', $content);
            $content = trim($content);
            
            $qcmData = json_decode($content, true);
            
            if (!isset($qcmData['questions']) || !is_array($qcmData['questions'])) {
                throw new \Exception('Format de réponse invalide');
            }
            
            return $qcmData['questions'];
            
        } catch (\Exception $e) {
            throw new \RuntimeException('Erreur lors de la génération du QCM: ' . $e->getMessage());
        }
    }
}
