import './App.css';
import 'bootstrap/dist/css/bootstrap.css';
import { Container, Row } from 'react-bootstrap';
import { MainNavbar } from './MainNavbar';
import { Router } from './Router';

function App() {
    return (
        <Container>
            <MainNavbar />
            <Container>
                <Router />
            </Container>
        </Container>
    );
}

export default App;
