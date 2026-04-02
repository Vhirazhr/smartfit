import axios from 'axios';

export async function classifyMorphotype(payload) {
    const response = await axios.post('/api/morphotype/classify', payload);
    return response.data.data;
}

export async function fetchRecommendations(payload) {
    const response = await axios.post('/api/recommendations', payload);
    return response.data.data;
}