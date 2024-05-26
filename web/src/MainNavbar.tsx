import { Container, Nav, NavLink, Navbar, NavbarBrand } from "react-bootstrap";

export function MainNavbar() {
    return (
        <Navbar className='bg-body-tertiary' expand='lg'>
            <Container>
                <Navbar.Brand href='/'>PHP dep</Navbar.Brand>
                <Navbar.Toggle aria-controls="basic-navbar-nav" />
                <Navbar.Collapse id="basic-navbar-nav">
                    <Nav className="me-auto">
                        <Nav.Link href="/graph">Graph</Nav.Link>
                    </Nav>
                </Navbar.Collapse>
            </Container>
        </Navbar>
    )
}